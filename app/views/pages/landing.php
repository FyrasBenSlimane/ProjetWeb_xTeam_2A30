<?php
// Landing page view for non-logged in users
?>

<!-- Modern Hero Section with Video Background - Fiverr Style -->
<section class="modern-hero-section fiverr-section-full" style="margin-top: 0 !important; padding-top: 0 !important;">
    <div class="video-background" style="position: fixed; top: 0; left: 0; width: 100%; height: 100vh; z-index: -1;">
        <video autoplay muted loop id="hero-video" style="width: 100%; height: 100%; object-fit: cover;">
            <source src="<?php echo URL_ROOT; ?>/public/images/background-video.mp4" type="video/mp4" preload="auto">
            <!-- Fallback background for browsers that don't support video -->
        </video>
        <div class="video-overlay" style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background: linear-gradient(135deg, rgba(0, 0, 0, 0.85) 0%, rgba(0, 0, 0, 0.6) 50%, rgba(0, 0, 0, 0.4) 100%);"></div>
    </div>

    <div class="hero-container fiverr-container">
        <div class="hero-content">
            <h1 class="hero-title">Connecting clients in <br>need to freelancers.</h1>

            <div class="hero-search-container">
                <form class="hero-search-form" action="<?php echo URL_ROOT; ?>/services/browse" method="GET">
                    <div class="search-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <input type="text" name="search" placeholder="Search for any service..." class="search-input">
                    <button type="submit" class="search-button">
                        <i class="fas fa-search"></i>
                    </button>
                </form>
            </div>

            <div class="popular-services">
                <a href="<?php echo URL_ROOT; ?>/services/browse?search=website" class="service-link">website development <i class="fas fa-arrow-right"></i></a>
                <a href="<?php echo URL_ROOT; ?>/services/browse?search=architecture" class="service-link">architecture & interior design <i class="fas fa-arrow-right"></i></a>
                <a href="<?php echo URL_ROOT; ?>/services/browse?search=ugc" class="service-link">UGC videos <i class="fas fa-arrow-right"></i></a>
                <a href="<?php echo URL_ROOT; ?>/services/browse?search=video" class="service-link">video editing <i class="fas fa-arrow-right"></i></a>
            </div>
        </div>
    </div>

    <div class="trusted-by-container">
        <div class="trusted-by-wrapper">
            <p class="trusted-by-text">Trusted by:</p>
            <div class="trusted-logos">
                <img src="https://fiverr-res.cloudinary.com/npm-assets/@fiverr/logged_out_homepage_perseus/meta.ff37dd3.svg" alt="Meta">
                <img src="https://fiverr-res.cloudinary.com/npm-assets/@fiverr/logged_out_homepage_perseus/google.e74f4d9.svg" alt="Google">
                <img src="https://fiverr-res.cloudinary.com/npm-assets/@fiverr/logged_out_homepage_perseus/netflix.b310314.svg" alt="Netflix">
                <img src="https://fiverr-res.cloudinary.com/npm-assets/@fiverr/logged_out_homepage_perseus/pg.22fca85.svg" alt="P&G">
                <img src="https://fiverr-res.cloudinary.com/npm-assets/@fiverr/logged_out_homepage_perseus/paypal.d398de5.svg" alt="PayPal">
                <img src="https://fiverr-res.cloudinary.com/npm-assets/@fiverr/logged_out_homepage_perseus/payoneer.7c1170d.svg" alt="Payoneer">
            </div>
        </div>
        <button class="video-control-button" aria-label="Play/Pause video">
            <i class="fas fa-play"></i>
        </button>
    </div>
</section>

<!-- Load GSAP before other scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/Flip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/CustomEase.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.12.2/ScrollToPlugin.min.js"></script>

<!-- CSS for Hero Section - Fiverr Style -->
<style>
    /* Modern Hero Section Styling */
    :root {
        /* Adding font variables from login page */
        --font-primary: "Poppins", "Helvetica Neue", Helvetica, Arial, sans-serif;
        --font-size-base-sm: 14px;
        --font-weight-base: 400;
        --font-weight-medium: 500;
        --font-weight-bold: 600;
        --line-height-base: 1.5;

        /* Primary color palette - more professional and minimalist */
        --primary: #2c3e50;
        /* Dark slate blue-gray, professional */
        --primary-light: #34495e;
        --primary-dark: #1a252f;
        --primary-accent: #ecf0f1;

        /* Secondary color palette - neutral and professional */
        --secondary: #222325;
        /* Dark gray for text */
        --secondary-light: #404145;
        --secondary-dark: #0e0e10;
        --secondary-accent: #f1f1f2;

        /* Accent colors for gradients */
        --accent-purple: #74767e;
        --accent-pink: #62646a;
        --accent-orange: #404145;

        /* Neutrals */
        --white: #ffffff;
        --text-dark: #222325;
        --gray-medium: #74767e;
        --gray-light: #e4e5e7;
        --gray-lighter: #fafafa;
        --gray-dark: #404145;

        /* UI elements */
        --shadow-sm: 0 2px 5px rgba(0, 0, 0, 0.05);
        --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.05);
        --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.08);
        --shadow-glow: 0 0 15px rgba(29, 191, 115, 0.3);
        --radius-sm: 4px;
        --radius-md: 8px;
        --radius-lg: 12px;
        --transition-fast: 0.2s ease;
        --transition-default: 0.3s ease;
        --container-max-width: 1400px;
        --container-padding: 32px;

        /* RGB values for opacity manipulations */
        --primary-rgb: 44, 62, 80;
        --secondary-rgb: 34, 35, 37;
        --accent-rgb: 116, 118, 126;
        
        /* Variables for responsive design */
        --mobile-padding: 16px;
        --tablet-padding: 24px;
        --desktop-padding: 32px;
        
        /* Underline width for dynamic changes */
        --underline-width: 0px;
    }

    body {
        margin: 0;
        padding: 0;
        font-family: var(--font-primary);
        overflow-x: hidden;
        /* Prevent horizontal scrolling */
    }

    .modern-hero-section {
        position: relative;
        height: 100vh;  /* Full viewport height */
        min-height: 680px;
        overflow: hidden;
        color: var(--white);
        width: 100%;
        margin: 0;
        padding: 0;
        font-family: var(--font-primary);
        margin-top: 0;
        padding-top: 0;
        box-sizing: border-box;
        z-index: 0;
    }

    .video-background {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: 0;
        overflow: hidden;
    }

    .video-background video {
        min-width: 100%;
        min-height: 100%;
        width: 100%;
        height: 100%;
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        object-fit: cover;
    }

    .video-overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: linear-gradient(135deg, rgba(0, 0, 0, 0.85) 0%, rgba(0, 0, 0, 0.6) 50%, rgba(0, 0, 0, 0.4) 100%);
        z-index: 1;
    }

    .hero-container {
        position: relative;
        z-index: 2;
        height: 100%;
        display: flex;
        align-items: center;
        width: 100%;
        max-width: var(--container-max-width);
        padding: 0 var(--desktop-padding);
        margin: 0 auto;
        box-sizing: border-box;
    }

    .hero-content {
        max-width: 1200px;
        width: 100%;
    }

    .hero-title {
        font-size: clamp(36px, 5vw, 72px);
        font-weight: 280;
        margin-bottom: 32px;
        color: var(--white);
        line-height: 1.2;
        font-family: var(--font-primary);
    }

    .hero-search-container {
        margin-bottom: 32px;
        width: 100%;
        position: relative;
        max-width: 600px;
    }

    .hero-search-form {
        display: flex;
        align-items: center;
        background-color: var(--white);
        border-radius: 12px;
        overflow: hidden;
        position: relative;
    }

    .search-icon {
        color: #62646a;
        position: absolute;
        left: 16px;
        font-size: 16px;
        z-index: 2;
    }

    .search-input {
        flex: 1;
        border: 1px solid #c5c6c9;
        border-radius: 12px;
        padding: 16px 16px 16px 40px;
        font-size: 16px;
        font-family: var(--font-primary);
        outline: none;
        color: #62646a;
        height: 48px;
        width: 100%;
        transition: border-color 0.3s ease;
        padding-right: 48px;
    }

    .search-input:focus {
        border-color: #222325;
        color: #62646a;
        box-shadow: none;
    }

    .search-button {
        border: none;
        background: #404145;
        color: var(--white);
        padding: 0;
        width: 40px;
        height: 40px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        position: absolute;
        right: 4px;
        top: 50%;
        transform: translateY(-50%);
        border-radius: 8px;
        z-index: 2;
    }

    .popular-services {
        display: flex;
        flex-wrap: wrap;
        gap: 16px;
        margin-top: 24px;
        padding-bottom: 8px;
        width: 100%;
    }

    .service-link {
        color: var(--white);
        text-decoration: none;
        padding: 10px 18px;
        border-radius: 8px;
        background-color: rgba(255, 255, 255, 0.12);
        font-size: 14px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        backdrop-filter: blur(5px);
        white-space: nowrap;
        margin-bottom: 8px;
    }

    .service-link:hover {
        background-color: rgba(255, 255, 255, 0.2);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        border-color: rgba(255, 255, 255, 0.2);
        color: var(--white);
        text-decoration: none;
    }

    .service-link:active {
        transform: translateY(0);
        transition: transform 0.1s;
    }

    .service-link i {
        font-size: 12px;
        transition: transform 0.3s ease;
    }
    
    .service-link:hover i {
        transform: translateX(3px);
    }
    
    .trusted-by-container {
        position: absolute;
        bottom: 30px; /* Changed from bottom: 0; to position it higher */
        left: 0;
        right: 0;
        z-index: 2;
        width: 100%;
        padding: 16px 0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        max-width: var(--container-max-width);
        margin: 0 auto;
        left: 50%;
        transform: translateX(-50%);
        box-sizing: border-box;
    }

    .trusted-by-wrapper {
        display: flex;
        align-items: center;
        gap: 24px;
        margin-left: var(--desktop-padding);
    }

    .trusted-by-text {
        color: var(--white);
        font-size: 14px;
        margin: 0;
    }

    .trusted-logos {
        display: flex;
        align-items: center;
        gap: 32px;
    }

    .trusted-logos img {
        height: 14px;
        object-fit: contain;
        opacity: 0.9;
        filter: brightness(1.2);
    }    
    
    .video-control-button {
        width: 42px;
        height: 42px;
        border-radius: 50%;
        background-color: rgba(0, 0, 0, 0.5);
        border: none;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        margin-right: var(--desktop-padding);
    }

    /* Global no-underline for hover states */
    a:hover {
        text-decoration: none !important;
        color: inherit;
    }

    /* Specific hover states that need preserving */
    .btn-outline:hover, 
    .btn-primary:hover, 
    .cta-button:hover,
    .testimonial-link:hover span,
    .tab-content__button:hover,
    .add-link:hover {
        text-decoration: none !important;
    }

    /* Buttons and links that need to maintain their original hover color */
    .add-link:hover {
        color: var(--primary-color);
    }

    .tab-content__button:hover .content-p {
        color: var(--white);
    }

    .cta-button.primary:hover {
        color: white;
    }

    /* Responsive design improvements */
    @media (max-width: 1200px) {
        .hero-container, .trusted-by-container {
            padding: 0 var(--tablet-padding);
        }
        
        .trusted-by-wrapper {
            margin-left: var(--tablet-padding);
        }
        
        .video-control-button {
            margin-right: var(--tablet-padding);
        }
    }

    @media (max-width: 768px) {
        .modern-hero-section {
            height: auto;
            min-height: 580px;
        }
        
        .hero-container {
            padding: 80px var(--mobile-padding) 60px;
            align-items: flex-start;
        }
        
        .hero-title {
            font-size: 36px;
            margin-bottom: 24px;
        }
        
        .hero-search-container {
            margin-bottom: 24px;
        }

        .popular-services {
            flex-direction: column;
            width: 100%;
            max-width: 100%;
            overflow-x: auto;
            flex-wrap: nowrap;
            padding-bottom: 16px;
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        }
        
        .service-link {
            width: 100%;
            justify-content: center;
        }
        
        .trusted-by-container {
            position: relative;
            flex-direction: column;
            padding: 16px var(--mobile-padding);
            transform: none;
            left: 0;
            margin-top: 40px;
        }

        .trusted-by-wrapper {
            flex-direction: column;
            margin-left: 0;
            gap: 16px;
            margin-bottom: 16px;
            width: 100%;
        }

        .trusted-logos {
            gap: 16px;
            flex-wrap: wrap;
            justify-content: center;
            width: 100%;
        }
        
        .video-control-button {
            margin-right: 0;
            margin-top: 16px;
        }
    }

    @media (max-width: 480px) {
        .hero-title {
            font-size: 28px;
        }
        
        .search-input {
            font-size: 14px;
            height: 46px;
        }
        
        .search-button {
            width: 36px;
            height: 36px;
        }

        .trusted-logos img {
            height: 12px;
        }
        
        .popular-services {
            grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
            gap: 10px;
        }
        
        .service-link {
            padding: 8px 14px;
            font-size: 12px;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Video playback control
        const video = document.getElementById('hero-video');
        const playButton = document.querySelector('.video-control-button');
        const heroSection = document.querySelector('.modern-hero-section');
        
        // Add landing-page class to body to apply specific styling
        document.body.classList.add('landing-page');
        
        // Set hero section height to viewport height
        function setHeroHeight() {
            const windowHeight = window.innerHeight;
            if (heroSection) {
                heroSection.style.height = `${windowHeight}px`;
            }
        }
        
        // Run on load and on resize
        window.addEventListener('resize', setHeroHeight);
        window.addEventListener('orientationchange', setHeroHeight);
        setHeroHeight();

        // Make sure hero section fully covers the screen
        if (heroSection) {
            heroSection.style.minHeight = '100vh';
        }

        // Navbar scroll behavior
        const navbar = document.querySelector('.navbar');
        if (navbar) {
            // Set initial state to transparent
            navbar.classList.add('transparent');
            
            // Handle scroll event
            window.addEventListener('scroll', function() {
                const scrollPosition = window.scrollY;
                const heroHeight = heroSection ? heroSection.offsetHeight - 100 : 500; // Trigger before reaching bottom
                
                if (scrollPosition > heroHeight) {
                    navbar.classList.remove('transparent');
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.add('transparent');
                    navbar.classList.remove('scrolled');
                }
            });
            
            // Trigger once on page load
            window.dispatchEvent(new Event('scroll'));
        }

        // For video player functionality
        if (playButton && video) {
            playButton.addEventListener('click', function() {
                if (video.paused) {
                    video.play();
                    playButton.innerHTML = '<i class="fas fa-pause"></i>';
                } else {
                    video.pause();
                    playButton.innerHTML = '<i class="fas fa-play"></i>';
                }
            });
        }
    });
</script>

<!-- Categories Section - Modern layout with professional design -->
<section class="category-section fiverr-section">
    <div class="container fiverr-container">
        <div class="fiverr-categories-container reveal fade-up">
            <div class="categories-grid-container">
            <a href="<?php echo URL_ROOT; ?>/services/browse?category=programming" class="fiverr-category-card">
                <div class="category-icon">
                <i class="fas fa-code"></i>
                </div>
                <span class="category-name">Programming & Tech</span>
            </a>

            <a href="<?php echo URL_ROOT; ?>/services/browse?category=design" class="fiverr-category-card">
                <div class="category-icon">
                <i class="fas fa-palette"></i>
                </div>
                <span class="category-name">Graphics & Design</span>
            </a>

            <a href="<?php echo URL_ROOT; ?>/services/browse?category=digital-marketing" class="fiverr-category-card">
                <div class="category-icon">
                <i class="fas fa-bullhorn"></i>
                </div>
                <span class="category-name">Digital Marketing</span>
            </a>

            <a href="<?php echo URL_ROOT; ?>/services/browse?category=writing" class="fiverr-category-card">
                <div class="category-icon">
                <i class="fas fa-pen-fancy"></i>
                </div>
                <span class="category-name">Writing & Translation</span>
            </a>

            <a href="<?php echo URL_ROOT; ?>/services/browse?category=video" class="fiverr-category-card">
                <div class="category-icon">
                <i class="fas fa-film"></i>
                </div>
                <span class="category-name">Video & Animation</span>
            </a>

            <a href="<?php echo URL_ROOT; ?>/services/browse?category=music" class="fiverr-category-card">
                <div class="category-icon">
                <i class="fas fa-music"></i>
                </div>
                <span class="category-name">Music & Audio</span>
            </a>

            <a href="<?php echo URL_ROOT; ?>/services/browse?category=business" class="fiverr-category-card">
                <div class="category-icon">
                <i class="fas fa-chart-line"></i>
                </div>
                <span class="category-name">Business</span>
            </a>

            <a href="<?php echo URL_ROOT; ?>/services/browse?category=ai-services" class="fiverr-category-card">
                <div class="category-icon">
                <i class="fas fa-robot"></i>
                </div>
                <span class="category-name">AI Services</span>
            </a>

            <a href="<?php echo URL_ROOT; ?>/services/browse?category=lifestyle" class="fiverr-category-card">
                <div class="category-icon">
                <i class="fas fa-heart"></i>
                </div>
                <span class="category-name">Lifestyle</span>
            </a>
            </div>
        </div>
        </div>
    </section>

    <!-- CSS for Categories Section -->
    <style>
        /* Professional Category Section Styling - Aligned with site theme */    
        .category-section {
        padding: 60px 0 90px;
        background: #ffffff;
        position: relative;
        overflow: hidden;
        border-top: 1px solid rgba(229, 231, 235, 0.3);
        border-bottom: 1px solid rgba(229, 231, 235, 0.3);
        }
        
        .fiverr-categories-container {
        position: relative;
        width: 100%;
        max-width: var(--container-max-width);
        transition: opacity 0.5s ease, transform 0.5s ease;
        overflow: hidden;
        margin: 0 auto;
        }  
        
        /* Horizontal scrollable container for categories */
        .categories-grid-container {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        padding: 20px 0 35px;
        width: 100%;
        overflow-x: auto;
        scrollbar-width: none; /* Hide scrollbar for Firefox */
        -ms-overflow-style: none; /* Hide scrollbar for IE and Edge */
        }
        
        .categories-grid-container::-webkit-scrollbar {
        display: none; /* Hide scrollbar for Chrome, Safari, and Opera */
        }

        .fiverr-category-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-decoration: none;
        padding: 15px 10px;
        border-radius: var(--radius-md);
        background-color: var(--white);
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(229, 231, 235, 0.8);
        text-align: center;
        width: 140px;
        min-width: 120px;
        height: 140px;
        position: relative;
        overflow: hidden;
        }
        
        .fiverr-category-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.08),
                        inset 0 0 24px 4px rgba(44, 62, 80, 0.10),
                        0 0 10px 2px rgba(44, 62, 80, 0.08);
            border-color: rgba(var(--primary-rgb), 0.2);
        }
        .fiverr-category-card:hover .category-name,
        .category-name,
        .fiverr-category-card:hover .category-name {
            text-decoration: none !important;
        }
        
        .fiverr-category-card:hover .category-icon {
        background-color: rgba(var(--primary-rgb), 0.08);
        transform: scale(1.05);
        }
        
        .fiverr-category-card:hover .category-name {
        color: var(--primary);
        }

        .category-icon {
        margin-bottom: 12px;
        width: 48px;
        height: 48px;
        background-color: rgba(245, 247, 250, 0.9);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        }

        .category-icon i {
        font-size: 20px;
        color: var(--primary);
        transition: all 0.3s ease;
        }    
        
        .category-name {
        font-size: 14px;
        font-weight: 500;
        color: var(--text-dark);
        line-height: 1.3;
        margin: 0;
        }

        .category-name::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        width: 0;
        height: 2px;
        background: linear-gradient(90deg, var(--primary) 0%, var(--primary-light) 100%);
        transition: all 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        transform: translateX(-50%);
        border-radius: 2px;
        opacity: 0.9;
        }
        
        .fiverr-category-card:hover .category-name {
        color: var(--primary);
        transform: translateY(-2px);
        }    
        
        .fiverr-category-card:hover .category-name::after {
        width: 40px;
        box-shadow: 0 1px 6px rgba(44, 62, 80, 0.12);
        }
        
        /* Reveal animations */
        .reveal {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.8s cubic-bezier(0.25, 0.1, 0.25, 1);
        transition-delay: 0.1s;
        }

        .reveal.active {
        opacity: 1;
        transform: translateY(0);
        }
          
        /* Category card staggered animations */
        .fiverr-categories-container {
        opacity: 0;
        transform: translateY(20px);
        transition: opacity 0.6s ease, transform 0.6s ease;
        }
        
        .fiverr-categories-container.active {
        opacity: 1;
        transform: translateY(0);
        }
        
        .fiverr-category-card {
        opacity: 0;
        transform: translateY(25px);
        transition: opacity 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94),
                transform 0.5s cubic-bezier(0.25, 0.46, 0.45, 0.94),
                box-shadow 0.4s ease,
                background 0.3s ease,
                border-color 0.3s ease;
        will-change: transform, opacity, box-shadow;
        }
        
        .fiverr-category-card.active {
        opacity: 1;
        transform: translateY(0);
        }
        
        /* Add mobile touch state */
        .fiverr-category-card.touch-active {
        transform: scale(0.98) translateY(-5px);
        transition: transform 0.2s ease;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.06);
        }

        .fade-up {
        transition-property: transform, opacity;
        }

        .reveal[data-delay="100"] {
        transition-delay: 0.1s;
        }

        .reveal[data-delay="200"] {
        transition-delay: 0.2s;
        }

        .reveal[data-delay="300"] {
        transition-delay: 0.3s;
        }

        .reveal[data-delay="400"] {
        transition-delay: 0.4s;
        }

        .reveal[data-delay="500"] {
        transition-delay: 0.5s;
        }

        .reveal[data-delay="600"] {
        transition-delay: 0.6s;
        }

        .reveal[data-delay="700"] {
        transition-delay: 0.7s;
        }

        .reveal[data-delay="800"] {
        transition-delay: 0.8s;
        }    

        /* Responsive grid adjustments */
        @media (max-width: 1200px) {
            .categories-grid-container {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        @media (max-width: 992px) {
            .categories-grid-container {
                grid-template-columns: repeat(3, 1fr);
            }
            
            .fiverr-category-card {
                padding: 18px 12px 15px;
            }

            .category-icon {
                width: 60px;
                height: 60px;
                margin-bottom: 14px;
            }
            
            .category-icon i {
                font-size: 22px;
            }
        }
          
        @media (max-width: 768px) {
            .categories-grid-container {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .category-icon {
                width: 55px;
                height: 55px;
                margin-bottom: 12px;
            }

            .category-icon i {
                font-size: 20px;
            }

            .fiverr-category-card {
                padding: 16px 10px 14px;
            }
            
            .fiverr-category-card:hover {
                transform: translateY(-6px);
            }
        }
          
        @media (max-width: 480px) {
            .categories-grid-container {
                grid-template-columns: repeat(2, 1fr);
                gap: 15px;
            }
            
            .fiverr-category-card {
                padding: 16px 10px 14px;
                height: 150px;
            }

            .category-icon {
                width: 50px;
                height: 50px;
                margin-bottom: 10px;
            }

            .category-icon i {
                font-size: 18px;
            }

            .category-name {
                font-size: 13px;
                padding-bottom: 6px;
            }
            
            .fiverr-category-card:hover {
                transform: translateY(-4px);
            }
        }
    </style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Reveal animation on scroll
        const revealElements = document.querySelectorAll('.reveal');

        function checkReveal() {
            const windowHeight = window.innerHeight;
            const revealPoint = 150; // pixels from the bottom of the viewport

            revealElements.forEach(element => {
                const revealTop = element.getBoundingClientRect().top;

                if (revealTop < windowHeight - revealPoint) {
                    element.classList.add('active');
                }
            });
        }

        // Check on page load and scroll
        window.addEventListener('scroll', checkReveal);
        window.addEventListener('resize', checkReveal);
        window.addEventListener('load', checkReveal);

        // Initialize on page load
        setTimeout(checkReveal, 100);
        
        // Staggered card reveal animation
        const categoryContainer = document.querySelector('.fiverr-categories-container');
        if (categoryContainer) {
            categoryContainer.classList.add('active');
            
            const categoryCards = categoryContainer.querySelectorAll('.fiverr-category-card');
            categoryCards.forEach((card, index) => {
                setTimeout(() => {
                    card.classList.add('active');
                }, 100 + (index * 50));
            });
        }
        
        // Enhanced hover effects for category cards
        const categoryCards = document.querySelectorAll('.fiverr-category-card');

        categoryCards.forEach((card, index) => {
            // Add progressive load animation
            setTimeout(() => {
                card.classList.add('active');
            }, 100 + (index * 70));
            
            // Check if GSAP is available
            if (typeof gsap !== 'undefined') {
                card.addEventListener('mouseenter', function() {
                    // Subtle animation for the whole card
                    gsap.to(this, {
                        duration: 0.4,
                        y: -8,
                        boxShadow: "0 15px 35px rgba(0, 0, 0, 0.07), 0 5px 15px rgba(0, 0, 0, 0.03)",
                        ease: "power3.out"
                    });

                    // Animate the icon
                    const categoryIcon = this.querySelector('.category-icon');
                    if (categoryIcon) {
                        gsap.to(categoryIcon, {
                            duration: 0.4,
                            y: -6,
                            scale: 1.05,
                            boxShadow: "0 12px 20px rgba(0, 0, 0, 0.1)",
                            ease: "back.out(1.7)"
                        });
                    }

                    // Animate the name underline
                    const categoryName = this.querySelector('.category-name');
                    if (categoryName) {
                        gsap.to(categoryName, {
                            duration: 0.3,
                            y: -1,
                            color: getComputedStyle(document.documentElement).getPropertyValue('--primary'),
                            ease: "power2.out"
                        });
                        
                        // Animate the underline using CSS variable
                        gsap.to(":root", {
                            duration: 0.4,
                            "--underline-width": "40px",
                            ease: "power2.out"
                        });
                    }
                });

                card.addEventListener('mouseleave', function() {
                    // Reset animations
                    gsap.to(this, {
                        duration: 0.5,
                        y: 0,
                        boxShadow: "0 10px 30px rgba(0, 0, 0, 0.04), 0 4px 8px rgba(0, 0, 0, 0.02)",
                        ease: "power3.out"
                    });

                    const categoryIcon = this.querySelector('.category-icon');
                    if (categoryIcon) {
                        gsap.to(categoryIcon, {
                            duration: 0.5,
                            y: 0,
                            scale: 1,
                            boxShadow: "0 6px 18px rgba(0, 0, 0, 0.06)",
                            ease: "power3.out"
                        });
                    }

                    const categoryName = this.querySelector('.category-name');
                    if (categoryName) {
                        gsap.to(categoryName, {
                            duration: 0.4,
                            y: 0,
                            color: getComputedStyle(document.documentElement).getPropertyValue('--text-dark'),
                            ease: "power2.out"
                        });
                        
                        // Reset the underline using CSS variable
                        gsap.to(":root", {
                            duration: 0.5,
                            "--underline-width": "0px",
                            ease: "power2.out"
                        });
                    }
                });
            } else {
                // Fallback for when GSAP is not available - use CSS transitions
                card.classList.add('no-gsap');
            }
            
            // Add touch events for mobile
            card.addEventListener('touchstart', function() {
                this.classList.add('touch-active');
            });
            
            card.addEventListener('touchend', function() {
                this.classList.remove('touch-active');
            });
        });
    });
</script>

<style>
    /* Fallback animations when GSAP is not available */
    .fiverr-category-card.no-gsap {
        transition: transform 0.4s ease, box-shadow 0.4s ease, background 0.4s ease;
    }
    
    .fiverr-category-card.no-gsap:hover {
        transform: translateY(-8px);
        box-shadow: 0 15px 35px rgba(0, 0, 0, 0.07), 0 5px 15px rgba(0, 0, 0, 0.03);
    }
    
    .fiverr-category-card.no-gsap .category-icon {
        transition: transform 0.4s ease, box-shadow 0.4s ease;
    }
    
    .fiverr-category-card.no-gsap:hover .category-icon {
        transform: translateY(-6px) scale(1.05);
        box-shadow: 0 12px 20px rgba(0, 0, 0, 0.1);
    }
    
    .fiverr-category-card.no-gsap .category-name {
        transition: transform 0.3s ease, color 0.3s ease;
    }
    
    .fiverr-category-card.no-gsap:hover .category-name {
        transform: translateY(-1px);
        color: var(--primary);
    }
    
    /* Refined effects for cards */
    .fiverr-category-card::after {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 4px;
        background: linear-gradient(90deg, var(--primary) 0%, var(--primary-dark) 100%);
        transform: scaleX(0);
        transform-origin: left;
        transition: transform 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    }

    .fiverr-category-card:hover::after {
        transform: scaleX(1);
    }

    /* Improved card styles with subtle shadow effects */
    .fiverr-categories-container {
        perspective: 1000px;
    }
    
    .fiverr-category-card {
        transform-style: preserve-3d;
        backface-visibility: hidden;
        will-change: transform;
    }
    
    .fiverr-category-card:before {
        content: '';
        position: absolute;
        inset: 0;
        border-radius: var(--radius-lg);
        padding: 2px;
        background: linear-gradient(145deg, rgba(255, 255, 255, 0.7), rgba(255, 255, 255, 0.1));
        -webkit-mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        mask: linear-gradient(#fff 0 0) content-box, linear-gradient(#fff 0 0);
        -webkit-mask-composite: xor;
        mask-composite: exclude;
        opacity: 0;
        transition: opacity 0.4s ease;
    }
    
    .fiverr-category-card:hover:before {
        opacity: 1;
    }
</style>

<!-- Modern Testimonials & Reviews Section -->
<section class="testimonials-section fiverr-section">
    <div class="container fiverr-container">
        <div class="section-header text-center mb-5">
            <h2 class="section-title">What Our Users Say</h2>
            <p class="section-subtitle">Discover how our platform is transforming careers and businesses around the globe</p>
        </div>

        <ul class="testimonial-grid">
            <li data-active="true">
                <article>
                    <h3>Client Success</h3>
                    <p>
                        "I was able to hire a top-notch developer within 24 hours. The quality of talent on this platform is exceptional. My project was completed ahead of schedule and exceeded expectations."
                    </p>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                    </svg>
                    <a href="#" class="testimonial-link">
                        <span>Sarah Johnson, Marketing Director</span>
                    </a>
                    <img src="https://images.unsplash.com/photo-1573496359142-b8d87734a5a2?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=776&q=80" alt="Sarah Johnson">
                </article>
            </li>
            <li>
                <article>
                    <h3>Freelancer Growth</h3>
                    <p>
                        "Since joining this platform, I've secured long-term clients and increased my income by 40%. The platform's payment protection gives me peace of mind and lets me focus on delivering quality work."
                    </p>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"></path>
                        <path d="m9 12 2 2 4-4"></path>
                    </svg>
                    <a href="#" class="testimonial-link">
                        <span>Michael Chen, UX Designer</span>
                    </a>
                    <img src="https://images.unsplash.com/photo-1568602471122-7832951cc4c5?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=870&q=80" alt="Michael Chen">
                </article>
            </li>
            <li>
                <article>
                    <h3>Enterprise Solution</h3>
                    <p>
                        "The platform's escrow system and clear milestone structure have streamlined our workflow with remote talent. We've built an incredible team of global professionals who consistently deliver outstanding results."
                    </p>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M21 16V8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16z"></path>
                        <polyline points="3.27 6.96 12 12.01 20.73 6.96"></polyline>
                        <line x1="12" y1="22.08" x2="12" y2="12"></line>
                    </svg>
                    <a href="#" class="testimonial-link">
                        <span>Robert Martinez, CTO</span>
                    </a>
                    <img src="https://images.unsplash.com/photo-1519085360753-af0119f7cbe7?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=774&q=80" alt="Robert Martinez">
                </article>
            </li>
            <li>
                <article>
                    <h3>Fast Hiring</h3>
                    <p>
                        "What impressed me most was the speed and quality. Within days, I connected with professionals who understood my vision and were able to execute flawlessly. This platform has become our go-to for all project needs."
                    </p>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 12h18"></path>
                        <path d="M12 3v18"></path>
                    </svg>
                    <a href="#" class="testimonial-link">
                        <span>Emma Lewis, Startup Founder</span>
                    </a>
                    <img src="https://images.unsplash.com/photo-1580489944761-15a19d654956?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=922&q=80" alt="Emma Lewis">
                </article>
            </li>
            <li>
                <article>
                    <h3>Career Advancement</h3>
                    <p>
                        "This platform has transformed my freelance career. The specialized categories and skill verification system helped me showcase my expertise to high-quality clients who value and properly compensate my work."
                    </p>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M18 8h1a4 4 0 0 1 0 8h-1"></path>
                        <path d="M2 8h16v9a4 4 0 0 1-4 4H6a4 4 0 0 1-4-4V8z"></path>
                        <line x1="6" y1="1" x2="6" y2="4"></line>
                        <line x1="10" y1="1" x2="10" y2="4"></line>
                        <line x1="14" y1="1" x2="14" y2="4"></line>
                    </svg>
                    <a href="#" class="testimonial-link">
                        <span>Daniel Wilson, Web Developer</span>
                    </a>
                    <img src="https://images.unsplash.com/photo-1599566150163-29194dcaad36?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=774&q=80" alt="Daniel Wilson">
                </article>
            </li>
            <li>
                <article>
                    <h3>Global Reach</h3>
                    <p>
                        "Being able to connect with clients worldwide has been game-changing. The platform handles all the complexity of international payments and contracts, letting me focus purely on delivering creative solutions."
                    </p>
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"></circle>
                        <line x1="2" y1="12" x2="22" y2="12"></line>
                        <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                    </svg>
                    <a href="#" class="testimonial-link">
                        <span>Anya Petrova, Graphic Designer</span>
                    </a>
                    <img src="https://images.unsplash.com/photo-1534751516642-a1af1ef26a56?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=778&q=80" alt="Anya Petrova">
                </article>
            </li>
        </ul>
    </div>
</section>

<!-- Styles for Interactive Testimonials -->
<style>
    .testimonials-section {
        padding: 100px 0;
        background-color: var(--white);
        position: relative;
        overflow: hidden;
    }

    .section-header {
        text-align: center;
        margin-bottom: 50px;
    }

    .section-title {
        font-size: 42px;
        font-weight: var(--font-weight-bold);
        color: var(--text-dark);
        margin-bottom: 16px;
    }

    .section-subtitle {
        font-size: 18px;
        color: var(--gray-medium);
        max-width: 600px;
        margin: 0 auto;
    }

    .testimonial-grid {
        display: grid;
        container-type: inline-size;
        grid-template-columns: 10fr 1fr 1fr 1fr 1fr 1fr;
        gap: 8px;
        list-style-type: none;
        justify-content: center;
        padding: 0;
        height: clamp(300px, 40vh, 474px);
        margin: 0 auto;
        width: 90%;
        max-width: 1140px;
        transition: grid-template-columns 0.6s cubic-bezier(0.6, 0.05, 0, 1);
    }

    .testimonial-grid li {
        background: var(--white);
        position: relative;
        overflow: hidden;
        min-width: calc(clamp(2rem, 8cqi, 80px));
        border-radius: var(--radius-md);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }

    .testimonial-grid article {
        width: calc(var(--article-width) * 1px);
        height: 100%;
        position: absolute;
        top: 0;
        left: 0;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        gap: 1rem;
        padding: 1.5rem;
        padding-bottom: 1rem;
        overflow: hidden;
    }

    .testimonial-grid h3 {
        position: absolute;
        top: 1rem;
        left: 1.5rem;
        transform-origin: 0 50%;
        rotate: 90deg;
        font-size: 1rem;
        font-weight: 500;
        text-transform: uppercase;
        color: #022b3a;
        opacity: 0.6;
        transition: opacity 0.7s cubic-bezier(0.6, 0.05, 0, 1);
    }

    .testimonial-grid svg {
        width: 18px;
        color: #022b3a;
        opacity: 0.6;
        transition: opacity 0.7s cubic-bezier(0.6, 0.05, 0, 1);
    }

    .testimonial-grid p {
        font-size: 0.95rem;
        line-height: 1.5;
        opacity: 0;
        transition: opacity 0.7s cubic-bezier(0.6, 0.05, 0, 1);
        margin-bottom: 1rem;
    }

    .testimonial-link {
        position: absolute;
        bottom: 1rem;
        left: 1.5rem;
        height: 18px;
        line-height: 1;
        color: #022b3a;
        opacity: 0;
        transition: opacity 0.7s cubic-bezier(0.6, 0.05, 0, 1);
    }

    .testimonial-link:hover span {
        text-decoration: underline;
        text-underline-offset: 4px;
    }

    .testimonial-link span {
        display: inline-block;
        line-height: 18px;
        font-weight: 500;
        font-size: 0.9rem;
    }

    .testimonial-grid img {
        position: absolute;
        pointer-events: none;
        inset: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        filter: grayscale(1) brightness(1.3);
        scale: 1.1;
        transition-property: filter, scale;
        transition-duration: 0.7s;
        transition-timing-function: cubic-bezier(0.6, 0.05, 0, 1);
        mask: radial-gradient(100% 100% at 100% 0, #fff, transparent);
    }

    .testimonial-grid [data-active="true"] h3,
    .testimonial-grid [data-active="true"] svg {
        opacity: 1;
    }

    .testimonial-grid [data-active="true"] p,
    .testimonial-grid [data-active="true"] .testimonial-link {
        opacity: 1;
        transition-delay: 0.15s;
    }

    .testimonial-grid [data-active="true"] img {
        filter: grayscale(0) brightness(1);
        scale: 1;
        transition-delay: 0.15s;
    }

    @media (max-width: 991px) {
        .testimonial-grid {
            grid-template-columns: 1fr;
            height: auto;
            gap: 1rem;
        }

        .testimonial-grid li {
            min-height: 200px;
            height: auto;
        }

        .testimonial-grid article {
            position: relative;
            width: 100%;
            padding: 1.5rem;
        }

        .testimonial-grid h3 {
            position: relative;
            top: 0;
            left: 0;
            rotate: 0deg;
            margin-bottom: 1rem;
            opacity: 1;
        }

        .testimonial-grid p,
        .testimonial-grid .testimonial-link {
            opacity: 1;
        }

        .testimonial-grid img {
            filter: grayscale(0) brightness(1);
            opacity: 0.15;
            z-index: -1;
        }

        .testimonial-link {
            position: relative;
            bottom: 0;
            left: 0;
        }
    }
</style>

<!-- Interactive Testimonials Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const list = document.querySelector('.testimonial-grid');
        const items = list.querySelectorAll('li');

        // Function to handle active testimonial switching
        const setActiveTestimonial = (event) => {
            const closest = event.target.closest('li');
            if (closest) {
                const index = [...items].indexOf(closest);
                const cols = new Array(list.children.length)
                    .fill()
                    .map((_, i) => {
                        items[i].dataset.active = (index === i).toString();
                        return index === i ? '10fr' : '1fr';
                    })
                    .join(' ');
                list.style.setProperty('grid-template-columns', cols);
            }
        };

        // Calculate article width for proper layout
        const resyncWidth = () => {
            const w = Math.max(...[...items].map(i => i.offsetWidth));
            list.style.setProperty('--article-width', w);
        };

        // Initialize event listeners
        list.addEventListener('focus', setActiveTestimonial, true);
        list.addEventListener('click', setActiveTestimonial);
        list.addEventListener('pointermove', setActiveTestimonial);

        // Custom cursor
        const createCursor = () => {
            const cursor = document.createElement('div');
            cursor.classList.add('circle');
            document.body.appendChild(cursor);

            // More responsive mouse tracking with requestAnimationFrame
            let mouseX = 0;
            let mouseY = 0;

            document.addEventListener('mousemove', (e) => {
                mouseX = e.clientX;
                mouseY = e.clientY;
            });

            // Use requestAnimationFrame for smoother cursor movement
            function updateCursor() {
                cursor.style.left = `${mouseX}px`;
                cursor.style.top = `${mouseY}px`;
                requestAnimationFrame(updateCursor);
            }

            requestAnimationFrame(updateCursor);
        };

        // Only create cursor on non-touch devices
        if (!('ontouchstart' in window)) {
            createCursor();
        }

        // Handle window resize events
        window.addEventListener('resize', resyncWidth);

        // Initialize on load
        resyncWidth();

        // Make sure first testimonial is active by default
        items[0].dataset.active = 'true';
    });
</script>
<!-- Interactive User Path Section -->
<section class="user-path-section fiverr-section">
    <div class="container fiverr-container">
        <div class="section-header text-center">
            <h2 class="section-title reveal fade-up">Join Our Marketplace</h2>
            <p class="section-subtitle reveal fade-up">Choose your path and start your journey today</p>
        </div>

        <div data-tabs="wrapper" class="tab-layout reveal fade-up">
            <div class="tab-layout-col">
                <div class="tab-layout-container">
                    <div class="tab-container">
                        <div class="tab-container-top">
                            <h3 class="tab-layout-heading">Discover the Perfect Solution for Your Needs</h3>
                            <div data-flip-button="wrap" data-tabs="nav" class="filter-bar">
                                <button data-tabs="button" data-flip-button="button" class="filter-button active">
                                    <div class="filter-button__p">Client</div>
                                    <div data-flip-button="bg" class="tab-button__bg"></div>
                                </button>
                                <button data-tabs="button" data-flip-button="button" class="filter-button">
                                    <div class="filter-button__p">Freelancer</div>
                                </button>
                                <button data-tabs="button" data-flip-button="button" class="filter-button">
                                    <div class="filter-button__p">Agency</div>
                                </button>
                            </div>
                        </div>
                        <div class="tab-container-bottom">
                            <div data-tabs="content-wrap" class="tab-content-wrap">
                                <div data-tabs="content-item" class="tab-content-item active">
                                    <h2 data-tabs-fade="" class="tab-content__heading">Hire the Best Talent</h2>
                                    <p data-tabs-fade="" class="content-p opacity--80">Access top-tier professionals from around the world. Post your project, review proposals, and collaborate seamlessly with the perfect match for your needs.</p>
                                </div>
                                <div data-tabs="content-item" class="tab-content-item">
                                    <h2 data-tabs-fade="" class="tab-content__heading">Showcase Your Skills</h2>
                                    <p data-tabs-fade="" class="content-p opacity--80">Find consistent work with clients looking for your exact expertise. Create a compelling profile, set your own rates, and build your professional reputation.</p>
                                </div>
                                <div data-tabs="content-item" class="tab-content-item">
                                    <h2 data-tabs-fade="" class="tab-content__heading">Scale Your Business</h2>
                                    <p data-tabs-fade="" class="content-p opacity--80">Manage multiple team members, handle large-scale projects, and increase your capacity with our enterprise-level tools and dedicated support.</p>
                                </div>
                            </div>
                            <a href="<?php echo URL_ROOT; ?>/users/register" class="tab-content__button w-inline-block">
                                <p class="content-p">Get started</p>
                                <div class="content-button__bg"></div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tab-layout-col">
                <div data-tabs="visual-wrap" class="tab-visual-wrap">
                    <div data-tabs="visual-item" class="tab-visual-item active">
                        <img src="https://images.pexels.com/photos/3184465/pexels-photo-3184465.jpeg?auto=compress&cs=tinysrgb&w=1200" loading="lazy" class="tab-image" alt="Client meeting with professionals">
                    </div>
                    <div data-tabs="visual-item" class="tab-visual-item">
                        <img src="https://images.pexels.com/photos/3764953/pexels-photo-3764953.jpeg?auto=compress&cs=tinysrgb&w=1200" loading="lazy" class="tab-image" alt="Freelancer working remotely">
                    </div>
                    <div data-tabs="visual-item" class="tab-visual-item">
                        <img src="https://images.pexels.com/photos/7688453/pexels-photo-7688453.jpeg?auto=compress&cs=tinysrgb&w=1200" loading="lazy" class="tab-image" alt="Agency team collaboration">
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CSS for the Interactive Tab System -->
<style>
    .user-path-section {
        padding: 100px 0;
        background-color: var(--white);
        position: relative;
        overflow: hidden;
        text-align: center;
    }
    
    .user-path-section .section-header {
        width: 100%;
        text-align: center;
        margin-bottom: 30px;
    }
    
    .user-path-section .section-title {
        font-size: 42px;
        font-weight: var(--font-weight-bold);
        color: var(--text-dark);
        margin-bottom: 16px;
        text-align: center;
    }
    
    .user-path-section .section-subtitle {
        font-size: 18px;
        color: var(--gray-medium);
        max-width: 600px;
        margin: 0 auto;
        text-align: center;
    }

    .tab-layout {
        z-index: 1;
        grid-row-gap: 3em;
        flex-flow: wrap;
        width: 100%;
        min-height: 37em;
        display: flex;
        position: relative;
        margin-top: 50px;
    }

    .tab-layout-col {
        width: 50%;
    }

    .tab-layout-container {
        width: 100%;
        max-width: 36em;
        height: 100%;
        margin-left: auto;
        margin-right: 0;
        padding-top: 1em;
        padding-bottom: 2em;
    }

    .tab-container {
        grid-column-gap: 3em;
        grid-row-gap: 3em;
        flex-flow: column;
        justify-content: space-between;
        align-items: flex-start;
        min-height: 100%;
        padding-top: 0;
        padding-bottom: 0;
        padding-right: 2.5em;
        display: flex;
    }

    .tab-container-top {
        grid-column-gap: 2em;
        grid-row-gap: 2em;
        flex-flow: column;
        justify-content: flex-start;
        align-items: flex-start;
        display: flex;
    }

    .tab-container-bottom {
        grid-column-gap: 2em;
        grid-row-gap: 2em;
        flex-flow: column;
        justify-content: flex-start;
        align-items: flex-start;
        display: flex;
    }

    .tab-layout-heading {
        margin-top: 0;
        margin-bottom: 1.5em;
        font-size: 2em;
        font-weight: var(--font-weight-bold);
        line-height: 1.2;
        color: var(--text-dark);
    }

    .filter-bar {
        background-color: rgba(var(--primary-rgb), 0.05);
        border: 1px solid rgba(var(--primary-rgb), 0.1);
        border-radius: 0.5em;
        padding: 0.5em;
        display: flex;
    }

    .filter-button {
        background-color: transparent;
        border: 1px solid transparent;
        padding: 1.125em 1.5em;
        transition: border-color 0.2s;
        position: relative;
        cursor: pointer;
    }

    .filter-button.active {
        border-color: rgba(var(--primary-rgb), 0.3);
        border-radius: 0.25em;
    }

    .filter-button__p {
        z-index: 1;
        font-size: 1.125em;
        position: relative;
        color: var(--text-dark);
        font-weight: var(--font-weight-medium);
    }

    .tab-button__bg {
        z-index: 0;
        background-color: rgba(var(--primary-rgb), 0.1);
        border: 1px solid rgba(var(--primary-rgb), 0.15);
        border-radius: 0.25em;
        width: 100%;
        height: 100%;
        position: absolute;
        inset: 0%;
        transition: all 0.3s cubic-bezier(0.25, 0.1, 0.25, 1.0);
        pointer-events: none;
        will-change: transform;
    }

    .filter-button.active .tab-button__bg {
        background-color: rgba(var(--primary-rgb), 0.2);
        border-color: rgba(var(--primary-rgb), 0.25);
    }

    .filter-button:hover .tab-button__bg {
        background-color: rgba(var(--primary-rgb), 0.15);
        transform: scale(1.02);
    }

    .tab-content-wrap {
        width: 100%;
        min-width: 24em;
        position: relative;
        min-height: 160px; /* Ensure minimum height to prevent layout shifts */
    }

    .tab-content-item {
        z-index: 1;
        grid-column-gap: 1.25em;
        grid-row-gap: 1.25em;
        opacity: 0;
        visibility: hidden;
        flex-flow: column;
        display: flex;
        position: absolute;
        inset: auto 0% 0%;
        transition: opacity 0.45s cubic-bezier(0.645, 0.045, 0.355, 1), visibility 0.45s cubic-bezier(0.645, 0.045, 0.355, 1);
        transform: translateY(15px);
        will-change: opacity, transform, visibility;
    }

    .tab-content-item.active {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
        transition: opacity 0.55s cubic-bezier(0.23, 1, 0.32, 1) 0.1s, transform 0.55s cubic-bezier(0.23, 1, 0.32, 1) 0.1s, visibility 0s linear;
    }

    .tab-content__heading {
        letter-spacing: -0.02em;
        margin-top: 0;
        margin-bottom: 0.5em;
        font-size: 1.75em;
        font-weight: var(--font-weight-bold);
        line-height: 1.2;
        color: var(--primary);
        transform: translateY(0);
        opacity: 1;
        transition: transform 0.6s cubic-bezier(0.23, 1, 0.32, 1), opacity 0.6s cubic-bezier(0.23, 1, 0.32, 1);
    }

    .content-p {
        margin: 0;
        font-size: 1.25em;
        line-height: 1.5;
        color: var(--text-dark);
        transform: translateY(0);
        opacity: 1;
        transition: transform 0.6s cubic-bezier(0.23, 1, 0.32, 1) 0.05s, opacity 0.6s cubic-bezier(0.23, 1, 0.32, 1) 0.05s;
    }

    .opacity--80 {
        opacity: 0.8;
    }

    .tab-content__button {
        background-color: transparent;
        justify-content: center;
        align-items: center;
        height: 4em;
        margin-top: 2em;
        padding-left: 2.5em;
        padding-right: 2.5em;
        text-decoration: none;
        border-radius: var(--radius-md);
        display: flex;
        position: relative;
        overflow: hidden;
        transition: all var(--transition-default);
        box-shadow: var(--shadow-sm);
    }

    .tab-content__button .content-p {
        color: var(--white);
        z-index: 1;
    }

    .tab-content__button:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-md);
    }

    .content-button__bg {
        z-index: -1;
        background-color: var(--primary);
        border-radius: var(--radius-md);
        position: absolute;
        inset: 0%;
    }

    .tab-visual-wrap {
        border-radius: var(--radius-lg);
        width: 100%;
        height: 42em;
        max-height: 80vh;
        position: relative;
        overflow: hidden;
        box-shadow: var(--shadow-lg);
    }

    .tab-visual-item {
        visibility: hidden;
        opacity: 0;
        justify-content: flex-start;
        align-items: center;
        width: 100%;
        height: 100%;
        display: flex;
        position: absolute;
        transform: translateX(3%);
        transition: opacity 0.65s cubic-bezier(0.645, 0.045, 0.355, 1), visibility 0.65s cubic-bezier(0.645, 0.045, 0.355, 1), transform 0.65s cubic-bezier(0.645, 0.045, 0.355, 1);
        will-change: opacity, transform, visibility;
    }

    .tab-visual-item.active {
        visibility: visible;
        opacity: 1;
        transform: translateX(0);
        transition: opacity 0.75s cubic-bezier(0.23, 1, 0.32, 1) 0.1s, transform 0.75s cubic-bezier(0.23, 1, 0.32, 1) 0.1s, visibility 0s linear;
    }

    .tab-image {
        object-fit: cover;
        border-radius: var(--radius-lg);
        width: 100%;
        max-width: none;
        height: 100%;
        transform: scale(1.05);
        transition: transform 1.2s cubic-bezier(0.23, 1, 0.32, 1);
    }
    
    .tab-visual-item.active .tab-image {
        transform: scale(1);
    }

    /* Animation for fade elements inside tabs */
    [data-tabs-fade] {
        transform: translateY(0);
        opacity: 1;
        transition: transform 0.6s cubic-bezier(0.23, 1, 0.32, 1), opacity 0.6s cubic-bezier(0.23, 1, 0.32, 1);
    }
    
    .tab-content-item:not(.active) [data-tabs-fade] {
        transform: translateY(20px);
        opacity: 0;
        transition: transform 0.5s cubic-bezier(0.645, 0.045, 0.355, 1), opacity 0.5s cubic-bezier(0.645, 0.045, 0.355, 1);
    }
    
    /* Staggered animation for multiple fade elements */
    .tab-content-item [data-tabs-fade]:nth-child(2) {
        transition-delay: 0.05s;
    }
    
    .tab-content-item [data-tabs-fade]:nth-child(3) {
        transition-delay: 0.1s;
    }

    @media (max-width: 991px) {
        .tab-layout {
            flex-direction: column-reverse;
        }

        .tab-layout-col {
            width: 100%;
        }

        .tab-layout-container {
            max-width: 100%;
            margin: 0 auto;
            padding: 2em 0;
        }

        .tab-container {
            padding-right: 0;
        }

        .tab-visual-wrap {
            height: 35em;
            margin-bottom: 3em;
        }
    }

    @media (max-width: 767px) {
        .tab-layout-heading {
            font-size: 1.7em;
        }

        .filter-button {
            padding: 0.9em 1.25em;
        }

        .filter-button__p {
            font-size: 1em;
        }

        .tab-content__heading {
            font-size: 1.5em;
        }

        .content-p {
            font-size: 1.1em;
        }

        .tab-visual-wrap {
            height: 25em;
        }
    }

    @media (max-width: 479px) {
        .tab-layout-heading {
            font-size: 1.5em;
        }

        .filter-bar {
            width: 100%;
        }

        .filter-button {
            padding: 0.8em 1em;
            flex: 1;
        }

        .filter-button__p {
            font-size: 0.9em;
        }

        .tab-content__heading {
            font-size: 1.4em;
        }

        .content-p {
            font-size: 1em;
        }

        .tab-visual-wrap {
            height: 20em;
        }
    }
</style>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Check if GSAP is available, but continue anyway
        const hasGSAP = typeof gsap !== 'undefined';
        const hasFlip = hasGSAP && typeof Flip !== 'undefined';
        
        if (!hasGSAP) {
            console.warn('GSAP not loaded. Using CSS fallback animations instead.');
            document.body.classList.add('no-gsap');
        }
        
        // Register plugins if available
        if (hasGSAP && typeof gsap.registerPlugin === 'function' && typeof CustomEase !== 'undefined' && hasFlip) {
            gsap.registerPlugin(CustomEase, Flip);
            try {
                CustomEase.create("osmo-ease", "0.625, 0.05, 0, 1");
                CustomEase.create("tab-ease", "0.23, 1, 0.32, 1");

                gsap.defaults({
                    ease: "tab-ease",
                    duration: 0.8,
                });
            } catch (e) {
                console.warn('Error initializing GSAP CustomEase:', e);
            }
        }

        // Initialize both tab systems and flip buttons
        initTabSystem();
        initFlipButtons();

        // Initialize Flip buttons functionality
        function initFlipButtons() {
            let wrappers = document.querySelectorAll('[data-flip-button="wrap"]');

            wrappers.forEach((wrapper) => {
                let buttons = wrapper.querySelectorAll('[data-flip-button="button"]');
                let bg = wrapper.querySelector('[data-flip-button="bg"]');

                if (!bg) return;
                
                // Add CSS transition for smooth animation fallback
                bg.style.transition = 'all 0.3s cubic-bezier(0.25, 0.1, 0.25, 1.0)';

                // Function to handle moving the background element
                const moveBgTo = (target) => {
                    if (hasGSAP && hasFlip) {
                        try {
                            // Use GSAP Flip if available
                            const state = Flip.getState(bg);
                            target.appendChild(bg);
                            Flip.from(state, {
                                duration: 0.4,
                                ease: "power2.out"
                            });
                        } catch (e) {
                            console.warn('Error with GSAP Flip:', e);
                            // Fallback to basic animation
                            target.appendChild(bg);
                        }
                    } else {
                        // Fallback to basic animation
                        target.appendChild(bg);
                    }
                };

                buttons.forEach(function(button) {
                    // Handle mouse enter
                    button.addEventListener("mouseenter", function() {
                        moveBgTo(this);
                    });

                    // Handle focus for keyboard navigation
                    button.addEventListener("focus", function() {
                        moveBgTo(this);
                    });

                    // Handle mouse leave and blur
                    const handleLeave = () => {
                        const activeLink = wrapper.querySelector(".active");
                        if (activeLink) moveBgTo(activeLink);
                    };
                    
                    button.addEventListener("mouseleave", handleLeave);
                    button.addEventListener("blur", handleLeave);
                    
                    // Handle click to persist the background
                    button.addEventListener("click", function() {
                        buttons.forEach(btn => btn.classList.remove("active"));
                        this.classList.add("active");
                        moveBgTo(this);
                    });
                });
            });
        }

        function initTabSystem() {
            let wrappers = document.querySelectorAll('[data-tabs="wrapper"]');

            wrappers.forEach((wrapper) => {
                let nav = wrapper.querySelector('[data-tabs="nav"]');
                if (!nav) return;

                let buttons = nav.querySelectorAll('[data-tabs="button"]');
                let contentWrap = wrapper.querySelector('[data-tabs="content-wrap"]');
                let contentItems = contentWrap ? contentWrap.querySelectorAll('[data-tabs="content-item"]') : [];
                let visualWrap = wrapper.querySelector('[data-tabs="visual-wrap"]');
                let visualItems = visualWrap ? visualWrap.querySelectorAll('[data-tabs="visual-item"]') : [];

                if (buttons.length === 0 || contentItems.length === 0) return;

                let activeButton = buttons[0];
                let activeContent = contentItems[0];
                let activeVisual = visualItems.length > 0 ? visualItems[0] : null;
                let isAnimating = false;

                function switchTab(index, initial = false) {
                    if (!initial && (isAnimating || buttons[index] === activeButton)) return; // ignore if clicked button is already active 
                    isAnimating = true;

                    const outgoingContent = activeContent;
                    const incomingContent = contentItems[index];
                    const outgoingVisual = activeVisual;
                    const incomingVisual = visualItems[index];

                    if (hasGSAP) {
                        try {
                            // Get all fade elements
                            let outgoingFadeElements = outgoingContent.querySelectorAll("[data-tabs-fade]");
                            let incomingFadeElements = incomingContent.querySelectorAll("[data-tabs-fade]");

                            // Build a professional animation timeline
                            const timeline = gsap.timeline({
                                defaults: {
                                    ease: "power2.out",
                                    duration: 0.6
                                },
                                onComplete: () => {
                                    if (!initial) {
                                        outgoingContent && outgoingContent.classList.remove("active");
                                        outgoingVisual && outgoingVisual.classList.remove("active");
                                    }
                                    activeContent = incomingContent;
                                    activeVisual = incomingVisual;
                                    isAnimating = false;
                                },
                            });

                            // Add to DOM immediately but invisible until animated
                            incomingContent.classList.add("active");
                            if (incomingVisual) incomingVisual.classList.add("active");

                            // First phase: fade out current content
                            timeline.to(outgoingFadeElements, {
                                y: "-20px",
                                opacity: 0,
                                stagger: 0.05,
                                duration: 0.5,
                                ease: "power2.inOut"
                            }, 0);

                            // Fade out current image with subtle scale
                            if (outgoingVisual) {
                                timeline.to(outgoingVisual, {
                                    opacity: 0,
                                    xPercent: -2,
                                    duration: 0.65,
                                    ease: "power2.inOut"
                                }, 0);
                                
                                // Add subtle scale to image (preserve src)
                                const outgoingImg = outgoingVisual.querySelector('.tab-image');
                                if (outgoingImg && outgoingImg.getAttribute('src') === "undefined") {
                                    // Restore image source if needed based on which tab we're coming from
                                    const currentIndex = Array.from(visualItems).indexOf(outgoingVisual);
                                    if (currentIndex === 0) outgoingImg.setAttribute('src', 'https://images.pexels.com/photos/3184465/pexels-photo-3184465.jpeg?auto=compress&cs=tinysrgb&w=1200');
                                    if (currentIndex === 1) outgoingImg.setAttribute('src', 'https://images.pexels.com/photos/3764953/pexels-photo-3764953.jpeg?auto=compress&cs=tinysrgb&w=1200');
                                    if (currentIndex === 2) outgoingImg.setAttribute('src', 'https://images.pexels.com/photos/7688453/pexels-photo-7688453.jpeg?auto=compress&cs=tinysrgb&w=1200');
                                }
                                
                                timeline.to(outgoingImg, {
                                    scale: 1.05,
                                    duration: 0.75,
                                    ease: "power2.inOut",
                                    clearProps: "src" // Prevent GSAP from modifying src
                                }, 0);
                            }

                            // Second phase: fade in new content with stagger
                            timeline.fromTo(incomingFadeElements, {
                                y: "20px",
                                opacity: 0
                            }, {
                                y: "0",
                                opacity: 1,
                                stagger: 0.08,
                                duration: 0.6,
                                ease: "power2.out"
                            }, 0.35);

                            // Fade in new image with subtle movement
                            if (incomingVisual) {
                                timeline.fromTo(incomingVisual, {
                                    opacity: 0,
                                    xPercent: 2
                                }, {
                                    opacity: 1,
                                    xPercent: 0,
                                    duration: 0.75,
                                    ease: "power2.out"
                                }, 0.35);
                                
                                // Add subtle scale to image (explicitly prevent src modification)
                                const incomingImg = incomingVisual.querySelector('.tab-image');
                                if (incomingImg && incomingImg.getAttribute('src') === "undefined") {
                                    // Fix broken image source if needed
                                    if (index === 0) incomingImg.setAttribute('src', 'https://images.pexels.com/photos/3184465/pexels-photo-3184465.jpeg?auto=compress&cs=tinysrgb&w=1200');
                                    if (index === 1) incomingImg.setAttribute('src', 'https://images.pexels.com/photos/3764953/pexels-photo-3764953.jpeg?auto=compress&cs=tinysrgb&w=1200');
                                    if (index === 2) incomingImg.setAttribute('src', 'https://images.pexels.com/photos/7688453/pexels-photo-7688453.jpeg?auto=compress&cs=tinysrgb&w=1200');
                                }
                                
                                timeline.fromTo(incomingImg, {
                                    scale: 1.05
                                }, {
                                    scale: 1,
                                    duration: 1.2,
                                    ease: "power2.out",
                                    clearProps: "src" // Prevent GSAP from touching the src attribute
                                }, 0.35);
                            }
                        } catch (e) {
                            console.warn('Error with GSAP animation:', e);
                            handleFallbackAnimation();
                        }
                    } else {
                        handleFallbackAnimation();
                    }
                    
                    function handleFallbackAnimation() {
                        // CSS-based animation fallback
                        // The transition is handled in CSS with classes
                        if (!initial) {
                            outgoingContent.classList.remove("active");
                            if (outgoingVisual) outgoingVisual.classList.remove("active");
                        }
                        
                        // Add active class to trigger CSS transitions
                        incomingContent.classList.add("active");
                        if (incomingVisual) incomingVisual.classList.add("active");
                        
                        // Set a timeout to allow the animation to complete before updating state
                        setTimeout(() => {
                            activeContent = incomingContent;
                            activeVisual = incomingVisual;
                            isAnimating = false;
                        }, 800); // Match this to the CSS transition duration
                    }

                    // Update button states
                    activeButton.classList.remove("active");
                    buttons[index].classList.add("active");
                    activeButton = buttons[index];
                }

                // Make sure all image sources are set correctly
                visualItems.forEach((item, i) => {
                    const img = item.querySelector('.tab-image');
                    if (img && (img.getAttribute('src') === "undefined" || !img.getAttribute('src'))) {
                        if (i === 0) img.setAttribute('src', 'https://images.pexels.com/photos/3184465/pexels-photo-3184465.jpeg?auto=compress&cs=tinysrgb&w=1200');
                        if (i === 1) img.setAttribute('src', 'https://images.pexels.com/photos/3764953/pexels-photo-3764953.jpeg?auto=compress&cs=tinysrgb&w=1200');
                        if (i === 2) img.setAttribute('src', 'https://images.pexels.com/photos/7688453/pexels-photo-7688453.jpeg?auto=compress&cs=tinysrgb&w=1200');
                    }
                });
                
                // Initialize the first tab
                switchTab(0, true);

                // Add click handlers to buttons
                buttons.forEach((button, i) => {
                    button.addEventListener("click", () => switchTab(i));
                });
            });
        }
        
        // Make sure all tab systems are responsive
        function updateTabSystemLayout() {
            const isSmallScreen = window.innerWidth < 768;
            document.querySelectorAll('.tab-layout').forEach(layout => {
                if (isSmallScreen) {
                    layout.classList.add('mobile-layout');
                } else {
                    layout.classList.remove('mobile-layout');
                }
            });
        }
        
        // Run on load and resize
        updateTabSystemLayout();
        window.addEventListener('resize', updateTabSystemLayout);
    });
</script>

<style>
    /* Additional responsive styles for tab system */
    .tab-layout.mobile-layout {
        flex-direction: column-reverse;
    }
    
    .tab-layout.mobile-layout .tab-layout-col {
        width: 100%;
    }
    
    .tab-layout.mobile-layout .tab-container {
        padding-right: 0;
    }
    
    .tab-layout.mobile-layout .tab-layout-container {
        max-width: 100%;
        margin: 0 auto;
        padding: 2em 0;
    }
    
    .tab-layout.mobile-layout .tab-visual-wrap {
        height: 30em;
        margin-bottom: 2em;
    }
    
    /* Responsive improvements for smaller screens */
    @media (max-width: 767px) {
        .cta-container {
            min-height: 350px;
            padding: 50px 20px;
        }
        
        .cta-heading {
            font-size: clamp(28px, 8vw, 42px);
        }
        
        .testimonial-grid {
            grid-template-columns: 1fr;
            height: auto;
        }
        
        .testimonial-grid li {
            min-height: 200px;
        }
        
        .tab-layout-heading {
            font-size: 1.5em;
        }
        
        .filter-button {
            padding: 0.8em 1em;
        }
        
        .filter-button__p {
            font-size: 0.9em;
        }
    }
    
    /* Fallback animations when GSAP is not available */
    .no-gsap .tab-content-item {
        transition: opacity 0.5s ease, transform 0.5s ease, visibility 0.5s ease;
    }
    
    .no-gsap .tab-visual-item {
        transition: opacity 0.5s ease, transform 0.5s ease, visibility 0.5s ease;
    }
    
    .no-gsap [data-tabs-fade] {
        transition: opacity 0.5s ease, transform 0.5s ease;
    }
</style>

<!-- CTA Section - Updated with glassmorphism design to complement video background -->
<div class="cta-wrapper fiverr-section-full footer-connector">
    <div class="cta-container fiverr-container glassmorphism">
        <div class="glass-overlay"></div>
        <h2 class="cta-heading">
            <span class="gradient-text">Freelance services at your fingertips</span>
        </h2>
        <div class="cta-buttons">
            <a href="<?php echo URL_ROOT; ?>/users/register" class="cta-button primary">Join LenSi</a>
        </div>
    </div>

    <!-- CSS for CTA Section with Glassmorphism -->
    <style>
        .cta-wrapper {
        width: 100%;
        padding: 100px 0;
        box-sizing: border-box;
        background: transparent; /* Make background transparent to see video */
        position: relative;
        z-index: 3; /* Position above video background */
        margin-bottom: 0;
        overflow: visible;
        }
        
        /* Connect CTA with footer */
        .footer-connector {
        margin-bottom: 0;
        }

        .cta-container {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 100px 30px;
        position: relative;
        max-width: var(--container-max-width);
        margin: 0 auto;
        overflow: hidden;
        position: relative;
        box-sizing: border-box;
        min-height: 436px;
        width: 100%;
        border: 1px solid rgba(255, 255, 255, 0.18);
        border-radius: 16px;
        }
        
        /* Glassmorphism effect */
        .glassmorphism {
        background: rgba(255, 255, 255, 0.08);
        backdrop-filter: blur(12px);
        -webkit-backdrop-filter: blur(12px);
        box-shadow: 0 8px 32px rgba(0, 0, 0, 0.2);
        }
        
        /* Additional glass overlay for depth */
        .glass-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: linear-gradient(
            135deg,
            rgba(255, 255, 255, 0.15) 0%,
            rgba(255, 255, 255, 0.05) 100%
        );
        z-index: 1;
        }

        .cta-heading {
        text-align: center;
        font-size: 54px;
        line-height: 1.2;
        font-weight: var(--font-weight-bold);
        margin-bottom: 30px;
        width: 100%;
        position: relative;
        z-index: 2;
        color: transparent; /* Make text transparent to show gradient */
        text-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        }

        /* Animated gradient text */
        .gradient-text {
        background-image: linear-gradient(
            120deg,
            rgba(255, 255, 255, 0.95) 0%,
            rgba(186, 231, 255, 0.95) 25%,
            rgba(255, 222, 189, 0.95) 50%,
            rgba(194, 255, 216, 0.95) 75%,
            rgba(255, 255, 255, 0.95) 100%
        );
        background-size: 200% auto;
        background-clip: text;
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        animation: gradient 8s ease infinite;
        display: inline-block;
        text-shadow: none;
        position: relative;
        }

        /* Subtle glow effect for the text */
        .gradient-text::after {
        content: "Freelance services at your fingertips";
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
        color: transparent;
        -webkit-text-stroke: 1px rgba(255, 255, 255, 0.1);
        filter: blur(8px);
        opacity: 0.5;
        transform: translateZ(0);
        }

        /* Gradient animation */
        @keyframes gradient {
        0% {
            background-position: 0% 50%;
        }
        50% {
            background-position: 100% 50%;
        }
        100% {
            background-position: 0% 50%;
        }
        }

        .cta-buttons {
        position: relative;
        z-index: 2;
        }

        .cta-button {
        display: inline-block;
        padding: 16px 40px;
        border: none;
        border-radius: 8px;
        font-weight: var(--font-weight-bold);
        font-size: 16px;
        line-height: 24px;
        text-decoration: none;
        transition: all 0.3s ease;
        position: relative;
        }

        .cta-button.primary {
        background: rgba(0, 0, 0, 0.7); /* Semi-transparent dark background */
        backdrop-filter: blur(5px);
        -webkit-backdrop-filter: blur(5px);
        color: white;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.25);
        border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .cta-button.primary:hover {
        background: rgba(0, 0, 0, 0.8);
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        }

        .cta-button.primary:active {
        transform: translateY(0);
        }

        .cta-button.primary:focus {
        outline: none;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3), 0 0 0 2px rgba(255, 255, 255, 0.2);
        }

        /* Responsive adjustments */
        @media screen and (max-width: 768px) {
        .cta-heading {
            font-size: 36px;
        }

        .cta-container {
            padding: 70px 20px;
            backdrop-filter: blur(8px); /* Less blur on mobile for better performance */
            -webkit-backdrop-filter: blur(8px);
        }

        .cta-button.primary {
            padding: 14px 32px;
        }
        }
        
        /* Fallback for browsers that don't support backdrop-filter or background-clip */
        @supports not ((backdrop-filter: blur(12px)) or (-webkit-background-clip: text)) {
        .glassmorphism {
            background: rgba(30, 40, 51, 0.85); /* Darker fallback */
        }
        
        .cta-button.primary {
            background: rgba(0, 0, 0, 0.85);
        }
        
        .gradient-text {
            background-image: none;
            -webkit-text-fill-color: rgba(255, 255, 255, 0.95);
            color: rgba(255, 255, 255, 0.95);
        }
        }
    </style>

    <!-- Optional JavaScript for dynamic gradient that reacts to video -->
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check if we can access video pixels (only works with same-origin videos)
        const video = document.getElementById('hero-video');
        const gradientText = document.querySelector('.gradient-text');
        
        if (video && gradientText) {
        // Create a more dynamic gradient based on scroll position
        window.addEventListener('scroll', function() {
            // Calculate scroll position percentage
            const scrollPosition = window.scrollY;
            const maxScroll = document.body.scrollHeight - window.innerHeight;
            const scrollPercentage = Math.min(scrollPosition / maxScroll * 100, 100);
            
            // Adjust gradient based on scroll
            const hueShift = Math.floor(scrollPercentage * 3.6); // 0-360 degrees
            
            // Apply dynamic gradient with scroll-influenced hue
            gradientText.style.backgroundImage = `linear-gradient(
            120deg,
            rgba(255, 255, 255, 0.95) 0%,
            hsl(${210 + hueShift}, 100%, 85%, 0.95) 25%,
            hsl(${30 + hueShift}, 100%, 85%, 0.95) 50%,
            hsl(${140 + hueShift}, 100%, 85%, 0.95) 75%,
            rgba(255, 255, 255, 0.95) 100%
            )`;
        });
        
        // Make gradient animation speed slightly random for more dynamic feel
        setInterval(function() {
            const animationDuration = 6 + Math.random() * 4; // Between 6-10s
            gradientText.style.animationDuration = `${animationDuration}s`;
        }, 8000);
        }
    });
    </script>
</div>

<style>
    /* Improved navbar styles for landing page */
    body.landing-page {
        padding-top: 0 !important; /* Remove padding on landing page */
    }
    
    /* Keep the floating effect on landing page */
    body.landing-page .navbar {
        position: fixed;
        width: 96% !important;
        max-width: 1300px !important;
        margin: 8px auto !important;
        left: 0;
        right: 0;
        border-radius: 6px !important;
        transition: all 0.3s ease !important;
    }
    
    /* Transparent state in hero section */
    body.landing-page .navbar.transparent {
        background-color: rgba(0, 0, 0, 0.05) !important;
        backdrop-filter: none !important;
        -webkit-backdrop-filter: none !important;
        box-shadow: none !important;
        border: 1px solid rgba(255, 255, 255, 0.1) !important;
    }
    
    /* Change logo color to white in transparent mode */
    body.landing-page .navbar.transparent .cls-1,
    body.landing-page .navbar.transparent .cls-2 {
        fill: #ffffff !important;
    }
    
    /* Change nav links to white in transparent mode */
    body.landing-page .navbar.transparent .nav-link,
    body.landing-page .navbar.transparent .navbar-nav .nav-link,
    body.landing-page .navbar.transparent .btn-link {
        color: #ffffff !important;
    }
    
    /* Scrolled state - below hero section */
    body.landing-page .navbar.scrolled {
        background-color: rgba(255, 255, 255, 0.9) !important;
        backdrop-filter: blur(12px) !important;
        -webkit-backdrop-filter: blur(12px) !important;
        border: 1px solid rgba(255, 255, 255, 0.3) !important;
        box-shadow: 0 6px 20px rgba(0, 0, 0, 0.06) !important;
    }
    
    /* Restore logo colors when scrolled */
    body.landing-page .navbar.scrolled .cls-1 {
        fill: #2c3e50 !important; /* Restore to primary color */
    }
    
    body.landing-page .navbar.scrolled .cls-2 {
        fill: #1a252f !important; /* Restore to secondary color */
    }
    
    /* Logo color transition animation */
    body.landing-page .navbar .cls-1,
    body.landing-page .navbar .cls-2 {
        transition: fill 0.3s ease !important;
    }
    
    /* Hide flash message margin on landing page */
    body.landing-page .navbar + div[style="margin-top: 20px;"] {
        display: none;
    }
</style>

<!-- Back to Top Button -->
<button id="back-to-top" aria-label="Back to top">
    <i class="fas fa-arrow-up"></i>
</button>

<!-- Styles for Back to Top Button -->
<style>
    #back-to-top {
        position: fixed;
        bottom: 30px;
        right: 30px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background-color: transparent;
        color: var(--primary);
        border: 2px solid var(--primary);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        visibility: hidden;
        transform: translateY(20px);
        transition: all 0.3s cubic-bezier(0.25, 0.46, 0.45, 0.94);
        z-index: 9999;
    }
    
    #back-to-top.visible {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }
    
    #back-to-top:hover {
        background-color: rgba(var(--primary-rgb), 0.05);
        transform: translateY(-5px);
        box-shadow: 0 6px 16px rgba(0, 0, 0, 0.2);
        border-color: var(--primary-light);
    }
    
    #back-to-top i {
        font-size: 20px;
        color: var(--primary);
        transition: transform 0.3s ease;
    }
    
    #back-to-top:hover i {
        transform: translateY(-3px);
    }
    
    @media (max-width: 768px) {
        #back-to-top {
            width: 45px;
            height: 45px;
            bottom: 20px;
            right: 20px;
        }
        
        #back-to-top i {
            font-size: 18px;
        }
    }
</style>

<!-- Back to Top Script -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const backToTopButton = document.getElementById('back-to-top');
        
        // Show button when page is scrolled down
        function toggleBackToTopButton() {
            if (window.scrollY > 300) {
                backToTopButton.classList.add('visible');
            } else {
                backToTopButton.classList.remove('visible');
            }
        }
        
        // Smooth scroll to top with animation
        function scrollToTop() {
            const scrollDuration = 500; // Faster animation (was 800)
            const scrollStep = -window.scrollY / (scrollDuration / 15);
            
            // Use GSAP if available for smoother animation
            if (typeof gsap !== 'undefined') {
                // Register ScrollToPlugin if needed
                if (gsap.registerPlugin && typeof ScrollToPlugin !== 'undefined') {
                    gsap.registerPlugin(ScrollToPlugin);
                }
                
                gsap.to(window, {
                    duration: 0.5, // Faster animation (was 0.8)
                    scrollTo: {
                        y: 0,
                        autoKill: false
                    },
                    ease: "power3.out", // More snappy easing
                    onStart: function() {
                        // Button press animation
                        gsap.to(backToTopButton, {
                            duration: 0.2, // Faster button animation
                            scale: 0.8,
                            yoyo: true,
                            repeat: 1,
                            ease: "back.out(2)"
                        });
                    }
                });
            } else {
                // Fallback smooth scroll with JS animation
                const scrollInterval = setInterval(function() {
                    if (window.scrollY !== 0) {
                        window.scrollBy(0, scrollStep);
                    } else {
                        clearInterval(scrollInterval);
                    }
                }, 15);
                
                // Simple button animation fallback
                backToTopButton.style.transform = 'scale(0.8)';
                setTimeout(() => {
                    backToTopButton.style.transform = 'scale(1)';
                }, 200); // Faster button animation (was 300)
            }
        }
        
        // Event listeners
        window.addEventListener('scroll', toggleBackToTopButton);
        backToTopButton.addEventListener('click', scrollToTop);
        
        // Initial check on page load
        toggleBackToTopButton();
    });
</script>