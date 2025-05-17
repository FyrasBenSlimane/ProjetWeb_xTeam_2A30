<?php
/**
 * Footer component with conditional display based on page type
 */

// Determine which page we're on (auth or other)
$isAuthPage = strpos($_SERVER['REQUEST_URI'], 'auth') !== false;
?>

</main>
    
<?php if($isAuthPage): // Simple footer for auth pages ?>
    <!-- Simplified footer for auth pages -->
    <footer class="auth-footer">
        <div class="container">
            <div class="row">
                <div class="col-md-12 text-center">
                    <a href="<?php echo URL_ROOT; ?>" class="footer-brand">
                        <svg id="Calque_1" data-name="Calque 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 286.56 90.12" width="82" height="26">
                            <defs>
                                <style>.cls-1{fill:#022b3a;}.cls-2{fill:#1f7a8c;}</style>
                            </defs>
                            <path class="cls-1" d="M146.56,330.77v-89H169v89Z" transform="translate(-146.56 -241.73)"/>
                            <path class="cls-1" d="M205.84,331.85a43.65,43.65,0,0,1-20.1-4.38,32.9,32.9,0,0,1-13.32-12,31.93,31.93,0,0,1-4.74-17.22,32.37,32.37,0,0,1,4.68-17.4A33,33,0,0,1,185.14,269a38,38,0,0,1,18.18-4.32,38.62,38.62,0,0,1,17.52,4,30.82,30.82,0,0,1,12.6,11.46q4.68,7.5,4.68,18.3,0,1.32-.12,2.82t-.24,2.7H186.28V292h39.48l-8.52,3.48a15,15,0,0,0-1.68-7.74,13.13,13.13,0,0,0-4.8-5.1,13.74,13.74,0,0,0-7.2-1.8,14.06,14.06,0,0,0-7.26,1.8,12.58,12.58,0,0,0-4.8,5.1,16.64,16.64,0,0,0-1.74,7.86v3.48a16.09,16.09,0,0,0,2,8.28,13.83,13.83,0,0,0,5.82,5.4,19.23,19.23,0,0,0,8.82,1.92,22,22,0,0,0,8.4-1.44,23.93,23.93,0,0,0,6.72-4.32l11.88,12.48A29.82,29.82,0,0,1,222,329.15,44.15,44.15,0,0,1,205.84,331.85Z" transform="translate(-146.56 -241.73)"/>
                            <path class="cls-1" d="M278.92,264.65a30.06,30.06,0,0,1,13.74,3.06,22.38,22.38,0,0,1,9.6,9.48q3.54,6.42,3.54,16.38v37.2H283.24v-33.6q0-6.84-2.88-10.08a10.05,10.05,0,0,0-7.92-3.24,13.91,13.91,0,0,0-6.72,1.62,11.11,11.11,0,0,0-4.68,5,20,20,0,0,0-1.68,8.88v31.44H236.92v-65h21.36v18.48l-4.08-5.4a24.75,24.75,0,0,1,10.08-10.56A29.63,29.63,0,0,1,278.92,264.65Z" transform="translate(-146.56 -241.73)"/>
                            <path class="cls-1" d="M331.84,331.85a67.53,67.53,0,0,1-16.2-1.92,46.12,46.12,0,0,1-12.6-4.8l6.84-15.48A38.64,38.64,0,0,0,320.44,314a46.78,46.78,0,0,0,12,1.62q5.76,0,8-1.2a3.69,3.69,0,0,0,2.28-3.36,3,3,0,0,0-2-2.76,20.66,20.66,0,0,0-5.46-1.5q-3.42-.54-7.5-1.08a67.78,67.78,0,0,1-8.22-1.62A28.92,28.92,0,0,1,312,301a15.56,15.56,0,0,1-5.52-5.64,18.16,18.16,0,0,1-2.1-9.24A17.54,17.54,0,0,1,308,275.21a24.93,24.93,0,0,1,10.68-7.68q7-2.88,17.22-2.88a68.84,68.84,0,0,1,13.86,1.44,42.1,42.1,0,0,1,11.82,4.08l-6.72,15.48a31.53,31.53,0,0,0-9.72-3.84,46.56,46.56,0,0,0-9.12-1q-5.76,0-8.16,1.32t-2.4,3.36a3.12,3.12,0,0,0,2,2.82,19.15,19.15,0,0,0,5.52,1.56q3.48.54,7.56,1.14a73.87,73.87,0,0,1,8.16,1.68,30.1,30.1,0,0,1,7.56,3.12,15.08,15.08,0,0,1,5.52,5.58,18.25,18.25,0,0,1,2,9.18,17.38,17.38,0,0,1-3.6,10.68q-3.6,4.8-10.8,7.68T331.84,331.85Z" transform="translate(-146.56 -241.73)"/>
                            <path class="cls-1" d="M362,330.77v-65H384.4v65Z" transform="translate(-146.56 -241.73)"/>
                            <path class="cls-2" d="M419.55,331.85a13.34,13.34,0,0,1-9.66-3.78,13,13,0,0,1-3.89-9.78,12.47,12.47,0,0,1,3.89-9.54,14.5,14.5,0,0,1,19.26,0,12.4,12.4,0,0,1,4,9.54,12.92,12.92,0,0,1-4,9.78A13.39,13.39,0,0,1,419.55,331.85Z" transform="translate(-146.56 -241.73)"/>
                        </svg>
                    </a>
                    <p class="copyright mt-2">
                        &copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <style>
        .auth-footer {
            position: relative;
            width: 100%;
            padding: 20px 0;
            background-color: #ffffff;
            border-top: 1px solid #e9ecef;
            text-align: center;
            margin-top: 2rem;
        }
        
        .footer-brand {
            display: inline-block;
            margin-bottom: 8px;
        }
        
        .copyright {
            color: #6c757d;
            font-size: 0.875rem;
            margin: 0;
        }
    </style>
<?php else: // Dark boxed footer for non-auth pages ?>
    <!-- White background wrapper to cover video -->
    <div class="footer-background-wrapper connect-with-cta">
        <!-- Modern Boxed Footer with Dark Theme -->
        <div class="footer-wrapper">
            <footer class="site-footer">
                <div class="footer-box">
                    <div class="footer-content">
                        <div class="row">
                            <!-- Categories Column -->
                            <div class="col-6 col-md-2 col-lg-2">
                                <div class="footer-widget animation-element mb-4 mb-lg-0 reveal">
                                    <h5 class="widget-title">Categories</h5>
                                    <ul class="footer-links">
                                        <li><a href="<?php echo URL_ROOT; ?>/services/browse?category=design" class="footer-link">Design & Creative</a></li>
                                        <li><a href="<?php echo URL_ROOT; ?>/services/browse?category=digital-marketing" class="footer-link">Digital Marketing</a></li>
                                        <li><a href="<?php echo URL_ROOT; ?>/services/browse?category=writing" class="footer-link">Writing & Translation</a></li>
                                        <li><a href="<?php echo URL_ROOT; ?>/services/browse?category=programming" class="footer-link">Programming & Tech</a></li>
                                        <li><a href="<?php echo URL_ROOT; ?>/services/browse?category=video" class="footer-link">Video & Animation</a></li>
                                        <li><a href="<?php echo URL_ROOT; ?>/services/browse?category=business" class="footer-link">Business</a></li>
                                    </ul>
                                </div>
                            </div>
                            
                            <!-- For Clients Column -->
                            <div class="col-6 col-md-2 col-lg-2">
                                <div class="footer-widget animation-element mb-4 mb-lg-0 reveal">
                                    <h5 class="widget-title">For Clients</h5>
                                    <ul class="footer-links">
                                        <li><a href="<?php echo URL_ROOT; ?>/services/browse" class="footer-link">Browse Services</a></li>
                                        <li><a href="<?php echo URL_ROOT; ?>/client" class="footer-link">Client Dashboard</a></li>
                                        <li><a href="<?php echo URL_ROOT; ?>/users/register" class="footer-link">Post a Job</a></li>
                                    </ul>
                                </div>
                            </div>
                            
                            <!-- For Freelancers Column -->
                            <div class="col-6 col-md-2 col-lg-2">
                                <div class="footer-widget animation-element mb-4 mb-lg-0 reveal">
                                    <h5 class="widget-title">For Freelancers</h5>
                                    <ul class="footer-links">
                                        <li><a href="<?php echo URL_ROOT; ?>/freelance" class="footer-link">Freelancer Dashboard</a></li>
                                        <li><a href="<?php echo URL_ROOT; ?>/community" class="footer-link">Community Hub</a></li>
                                        <li><a href="<?php echo URL_ROOT; ?>/services/browse" class="footer-link">Find Work</a></li>
                                    </ul>
                                </div>
                            </div>
                            
                            <!-- Company Column -->
                            <div class="col-6 col-md-2 col-lg-2">
                                <div class="footer-widget animation-element mb-4 mb-lg-0 reveal-right">
                                    <h5 class="widget-title">Company</h5>
                                    <ul class="footer-links">
                                        <li><a href="<?php echo URL_ROOT; ?>/support" class="footer-link">Help & Support</a></li>
                                        <li><a href="<?php echo URL_ROOT; ?>/terms" class="footer-link">Terms of Service</a></li>
                                        <li><a href="<?php echo URL_ROOT; ?>/privacy" class="footer-link">Privacy Policy</a></li>
                                        <li><a href="<?php echo URL_ROOT; ?>/users/login" class="footer-link">Sign In</a></li>
                                        <li><a href="<?php echo URL_ROOT; ?>/users/register" class="footer-link">Join</a></li>
                                    </ul>
                                </div>
                            </div>
                            
                            <!-- Logo & Social Column -->
                            <div class="col-md-2 col-lg-2">
                                <div class="footer-widget animation-element mb-4 mb-lg-0 reveal-right">
                                    <div class="footer-logo mb-3">
                                        <svg id="Calque_1" data-name="Calque 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 286.56 90.12" width="95" height="30">
                                            <defs>
                                                <style>.cls-1{fill:#e0e0e0;}.cls-2{fill:#4e9bff;}</style>
                                            </defs>
                                            <path class="cls-1" d="M146.56,330.77v-89H169v89Z" transform="translate(-146.56 -241.73)"/>
                                            <path class="cls-1" d="M205.84,331.85a43.65,43.65,0,0,1-20.1-4.38,32.9,32.9,0,0,1-13.32-12,31.93,31.93,0,0,1-4.74-17.22,32.37,32.37,0,0,1,4.68-17.4A33,33,0,0,1,185.14,269a38,38,0,0,1,18.18-4.32,38.62,38.62,0,0,1,17.52,4,30.82,30.82,0,0,1,12.6,11.46q4.68,7.5,4.68,18.3,0,1.32-.12,2.82t-.24,2.7H186.28V292h39.48l-8.52,3.48a15,15,0,0,0-1.68-7.74,13.13,13.13,0,0,0-4.8-5.1,13.74,13.74,0,0,0-7.2-1.8,14.06,14.06,0,0,0-7.26,1.8,12.58,12.58,0,0,0-4.8,5.1,16.64,16.64,0,0,0-1.74,7.86v3.48a16.09,16.09,0,0,0,2,8.28,13.83,13.83,0,0,0,5.82,5.4,19.23,19.23,0,0,0,8.82,1.92,22,22,0,0,0,8.4-1.44,23.93,23.93,0,0,0,6.72-4.32l11.88,12.48A29.82,29.82,0,0,1,222,329.15,44.15,44.15,0,0,1,205.84,331.85Z" transform="translate(-146.56 -241.73)"/>
                                            <path class="cls-1" d="M278.92,264.65a30.06,30.06,0,0,1,13.74,3.06,22.38,22.38,0,0,1,9.6,9.48q3.54,6.42,3.54,16.38v37.2H283.24v-33.6q0-6.84-2.88-10.08a10.05,10.05,0,0,0-7.92-3.24,13.91,13.91,0,0,0-6.72,1.62,11.11,11.11,0,0,0-4.68,5,20,20,0,0,0-1.68,8.88v31.44H236.92v-65h21.36v18.48l-4.08-5.4a24.75,24.75,0,0,1,10.08-10.56A29.63,29.63,0,0,1,278.92,264.65Z" transform="translate(-146.56 -241.73)"/>
                                            <path class="cls-1" d="M331.84,331.85a67.53,67.53,0,0,1-16.2-1.92,46.12,46.12,0,0,1-12.6-4.8l6.84-15.48A38.64,38.64,0,0,0,320.44,314a46.78,46.78,0,0,0,12,1.62q5.76,0,8-1.2a3.69,3.69,0,0,0,2.28-3.36,3,3,0,0,0-2-2.76,20.66,20.66,0,0,0-5.46-1.5q-3.42-.54-7.5-1.08a67.78,67.78,0,0,1-8.22-1.62A28.92,28.92,0,0,1,312,301a15.56,15.56,0,0,1-5.52-5.64,18.16,18.16,0,0,1-2.1-9.24A17.54,17.54,0,0,1,308,275.21a24.93,24.93,0,0,1,10.68-7.68q7-2.88,17.22-2.88a68.84,68.84,0,0,1,13.86,1.44,42.1,42.1,0,0,1,11.82,4.08l-6.72,15.48a31.53,31.53,0,0,0-9.72-3.84,46.56,46.56,0,0,0-9.12-1q-5.76,0-8.16,1.32t-2.4,3.36a3.12,3.12,0,0,0,2,2.82,19.15,19.15,0,0,0,5.52,1.56q3.48.54,7.56,1.14a73.87,73.87,0,0,1,8.16,1.68,30.1,30.1,0,0,1,7.56,3.12,15.08,15.08,0,0,1,5.52,5.58,18.25,18.25,0,0,1,2,9.18,17.38,17.38,0,0,1-3.6,10.68q-3.6,4.8-10.8,7.68T331.84,331.85Z" transform="translate(-146.56 -241.73)"/>
                                            <path class="cls-1" d="M362,330.77v-65H384.4v65Z" transform="translate(-146.56 -241.73)"/>
                                            <path class="cls-2" d="M419.55,331.85a13.34,13.34,0,0,1-9.66-3.78,13,13,0,0,1-3.89-9.78,12.47,12.47,0,0,1,3.89-9.54,14.5,14.5,0,0,1,19.26,0,12.4,12.4,0,0,1,4,9.54,12.92,12.92,0,0,1-4,9.78A13.39,13.39,0,0,1,419.55,331.85Z" transform="translate(-146.56 -241.73)"/>
                                        </svg>
                                    </div>
                                    <div class="social-links mt-3">
                                        <a href="#" class="social-link"><i class="fab fa-tiktok"></i></a>
                                        <a href="#" class="social-link"><i class="fab fa-instagram"></i></a>
                                        <a href="#" class="social-link"><i class="fab fa-linkedin-in"></i></a>
                                        <a href="https://www.facebook.com/profile.php?id=61576228714380" class="social-link"><i class="fab fa-facebook-f"></i></a>
                                        <a href="#" class="social-link"><i class="fab fa-pinterest-p"></i></a>
                                        <a href="#" class="social-link"><i class="fab fa-twitter"></i></a>
                                    </div>
                                    <div class="mt-4">
                                        <div class="language-select-container">
                                            <select id="language-selector" class="language-select">
                                                <option value="en" selected>English</option>
                                                <option value="es">Español</option>
                                                <option value="fr">Français</option>
                                                <option value="de">Deutsch</option>
                                                <option value="it">Italiano</option>
                                                <option value="pt">Português</option>
                                                <option value="nl">Nederlands</option>
                                                <option value="pl">Polski</option>
                                                <option value="ru">Русский</option>
                                                <option value="ja">日本語</option>
                                                <option value="zh">中文</option>
                                                <option value="ar">العربية</option>
                                                <option value="hi">हिन्दी</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="footer-bottom">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <p class="copyright mb-0">
                                    &copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved.
                                </p>
                            </div>
                            <div class="col-md-6 text-md-end">
                                <div class="footer-legal-links">
                                    <a href="<?php echo URL_ROOT; ?>/terms" class="legal-link">Terms</a>
                                    <a href="<?php echo URL_ROOT; ?>/privacy" class="legal-link">Privacy</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Footer Style -->
    <style>
        /* White background wrapper to cover video */
        .footer-background-wrapper {
            background-color: #ffffff;
            width: 100%;
            position: relative;
            z-index: 10;
            margin-top: 3rem;
            padding-bottom: 3rem;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        
        /* Remove space between CTA and footer */
        .connect-with-cta {
            margin-top: 0;
            padding-top: 4rem; /* Add padding to create space between CTA and footer */
        }
        
        /* Dark boxed footer styling */
        .footer-wrapper {
            padding: 0 20px;
            margin: 0 auto;
            max-width: 1400px;
            background-color: #ffffff;
            width: 100%;
            position: relative;
            z-index: 10;
        }
        
        /* Hide Google Translate bar */
        .goog-te-banner-frame {
            display: none !important;
        }
        
        .goog-te-gadget {
            font-family: inherit !important;
            font-size: 0 !important;
        }
        
        .goog-te-gadget .goog-te-combo {
            display: none !important;
        }
        
        .goog-te-menu-value {
            display: none !important;
        }
        
        /* Fix Google Translate body shift */
        body {
            top: 0 !important;
        }
        
        /* Target the Google Translate dropdown */
        .skiptranslate {
            display: none !important;
        }
        
        .site-footer {
            position: relative;
            color: #e0e0e0;
            overflow: hidden;
            margin-top: 2rem;
        }
        
        .footer-box {
            background: #181818;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            border: 1px solid #2c2c2c;
        }

        .footer-content {
            position: relative;
            padding: 40px;
        }

        .footer-bottom {
            padding: 20px 40px;
            margin-top: 0;
            border-top: 1px solid #2c2c2c;
            position: relative;
            background-color: #222222;
        }

        .widget-title {
            color: #ffffff;
            font-weight: 600;
            margin-bottom: 1rem;
            font-size: 1.1rem;
            position: relative;
            display: inline-block;
        }

        .footer-description {
            color: #b0b0b0;
            line-height: 1.5;
            font-size: 14px;
            margin-bottom: 0.5rem;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-link {
            color: #b0b0b0;
            text-decoration: none;
            display: block;
            padding: 5px 0;
            font-size: 13px;
            transition: all 0.2s ease-out;
        }

        .footer-link:hover {
            color: #4e9bff;
            transform: translateX(3px);
        }

        .social-links {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
        }

        .social-link {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 32px;
            height: 32px;
            border-radius: 50%;
            background-color: #333333;
            color: #e0e0e0;
            text-decoration: none;
            transition: all 0.2s ease-in-out;
            font-size: 14px;
            border: 1px solid #444444;
        }

        .social-link:hover {
            background-color: #4e9bff;
            color: #ffffff;
            border-color: #4e9bff;
            transform: translateY(-2px);
        }

        .copyright {
            color: #b0b0b0;
            font-size: 0.875rem;
        }

        .footer-legal-links {
            display: flex;
            gap: 1.5rem;
            justify-content: flex-end;
        }

        .legal-link {
            color: #b0b0b0;
            text-decoration: none;
            font-size: 0.875rem;
            transition: color 0.2s ease-in-out;
        }

        .legal-link:hover {
            color: #4e9bff;
        }
        
        .contact-info {
            display: flex;
            align-items: center;
            color: #b0b0b0;
            margin-bottom: 8px;
            font-size: 14px;
        }

        .contact-info i {
            width: 16px;
            color: #4e9bff;
            font-size: 14px;
            margin-right: 8px;
        }
        
        .footer-logo svg {
            margin-bottom: 10px;
        }
        
        .language-select {
            color: #b0b0b0;
            font-size: 13px;
            cursor: pointer;
            padding: 6px 12px;
            border-radius: 4px;
            border: 1px solid #444444;
            transition: all 0.2s;
            background-color: #222222;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='none' stroke='%23b0b0b0' stroke-width='2' stroke-linecap='round' stroke-linejoin='round'%3e%3cpolyline points='6 9 12 15 18 9'%3e%3c/polyline%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 8px center;
            background-size: 12px;
            padding-right: 28px;
            width: 100%;
        }
        
        .language-select:hover, .language-select:focus {
            border-color: #4e9bff;
            color: #ffffff;
            outline: none;
        }
        
        .language-select-container {
            position: relative;
            display: inline-flex;
            align-items: center;
        }
        
        .language-select-container::before {
            content: "\f0ac";
            font-family: "Font Awesome 5 Free";
            font-weight: 900;
            color: #b0b0b0;
            position: absolute;
            left: 8px;
            top: 50%;
            transform: translateY(-50%);
            z-index: 1;
            pointer-events: none;
        }
        
        .language-select {
            padding-left: 28px;
        }

        @media (max-width: 991px) {
            .footer-widget {
                margin-bottom: 2rem;
            }
            
            .footer-content {
                padding: 30px 20px;
            }
            
            .footer-bottom {
                padding: 20px;
            }
            
            .footer-wrapper {
                padding: 0 15px;
                margin: 2rem auto;
                background-color: #ffffff;
            }
        }
        
        @media (max-width: 767px) {
            .footer-legal-links {
                justify-content: flex-start;
                margin-top: 12px;
                gap: 1rem;
            }
            
            .col-6 {
                margin-bottom: 1.5rem;
            }
            
            .footer-content .row {
                row-gap: 0;
            }
        }
    </style>
<?php endif; ?>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Main JavaScript -->
    <script src="<?php echo URL_ROOT; ?>/public/js/main.js"></script>

    <!-- Auth JavaScript -->
    <?php if(strpos($_SERVER['REQUEST_URI'], 'auth') !== false || strpos($_SERVER['REQUEST_URI'], 'login') !== false): ?>
    <script src="<?php echo URL_ROOT; ?>/public/js/auth/google-auth.js"></script>
    <script src="<?php echo URL_ROOT; ?>/public/js/auth/github-auth.js"></script>
    <?php endif; ?>
    
    <!-- Page-specific JavaScript -->
    <?php if(isset($data['js'])) : ?>
        <?php foreach($data['js'] as $js) : ?>
            <script src="<?php echo URL_ROOT; ?>/public/js/<?php echo $js; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Global Modal Script -->
    <script src="<?php echo URL_ROOT; ?>/public/js/components/modal.js"></script>

    <!-- Google Translate API Script -->
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
                autoDisplay: false,
                includedLanguages: 'ar,de,en,es,fr,hi,it,ja,nl,pl,pt,ru,zh'
            }, 'google_translate_element');
        }

        // Get user's browser language or stored preference
        function getUserLanguage() {
            let storedLang = localStorage.getItem('lensi_language');
            if (storedLang) {
                return storedLang;
            } else {
                // Get browser language
                let browserLang = navigator.language || navigator.userLanguage;
                browserLang = browserLang.split('-')[0]; // Get the language code without region
                return browserLang;
            }
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Create a hidden div for Google Translate
            const translateDiv = document.createElement('div');
            translateDiv.id = 'google_translate_element';
            translateDiv.style.display = 'none';
            document.body.appendChild(translateDiv);
            
            // Load Google Translate script
            const script = document.createElement('script');
            script.src = 'https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit';
            script.async = true;
            document.body.appendChild(script);
            
            // Initialize language selector
            const languageSelector = document.getElementById('language-selector');
            if (languageSelector) {
                // Set initial value from user preference
                const userLang = getUserLanguage();
                if (userLang && languageSelector.querySelector(`option[value="${userLang}"]`)) {
                    languageSelector.value = userLang;
                }
                
                // Handle language change
                languageSelector.addEventListener('change', function() {
                    const lang = this.value;
                    
                    // Store user language preference
                    localStorage.setItem('lensi_language', lang);
                    
                    // Change language using Google Translate
                    const selectElement = document.querySelector('.goog-te-combo');
                    if (selectElement) {
                        selectElement.value = lang;
                        selectElement.dispatchEvent(new Event('change'));
                    } else {
                        // If Google Translate isn't loaded yet, set a cookie and reload
                        document.cookie = `googtrans=/en/${lang}; path=/; domain=.${window.location.hostname}`;
                        document.cookie = `googtrans=/en/${lang}; path=/;`;
                        window.location.reload();
                    }
                });
                
                // Auto-translate on page load 
                window.addEventListener('load', function() {
                    // Wait for Google Translate to initialize
                    setTimeout(function() {
                        const userLang = getUserLanguage();
                        if (userLang && userLang !== 'en') {
                            const selectElement = document.querySelector('.goog-te-combo');
                            if (selectElement) {
                                selectElement.value = userLang;
                                selectElement.dispatchEvent(new Event('change'));
                            }
                        }
                    }, 1000);
                });
            }
        });
    </script>
</body>
</html>
