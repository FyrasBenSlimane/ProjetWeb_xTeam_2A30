<?php
// Landing page view for non-logged in users
?>

<!-- Modern Hero Section with Video Background -->
<!-- Required libraries for animations -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/three@0.128.0/examples/js/controls/OrbitControls.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dat-gui/0.7.9/dat.gui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/gsap.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/CustomEase.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.11.4/Flip.min.js"></script>

<section class="modern-hero-section">
    <!-- Mouse effect for interactive cursor -->
    <div class="mouse-effect">
        <div class="circle"></div>
        <div class="circle-follow"></div>
    </div>
    <div class="video-background">
        <video autoplay muted loop id="hero-video">
            <source src="https://videos.pexels.com/video-files/5138207/5138207-uhd_2732_1440_25fps.mp4" type="video/mp4" preload="auto">
            <!-- Fallback background for browsers that don't support video -->
        </video>
        <div class="video-overlay"></div>
    </div>
    
    <div class="hero-container">
        <div class="hero-content">
            <h1 class="hero-title">Unleash your <span class="gradient-text">potential</span><br>with top global talent</h1>
            <p class="hero-subtitle">Your vision, our professionals. Seamless connections that transform ideas into reality.</p>
            
            <div class="hero-search-container">
                <form class="hero-search-form" action="<?php echo URL_ROOT; ?>/services/browse" method="GET">
                    <div class="search-icon">
                        <i class="fas fa-search"></i>
                    </div>
                    <input type="text" name="search" placeholder="What service are you looking for today?" class="pulse-animation">
                    <button type="submit" class="search-button">Find Services</button>
                </form>
            </div>
            
            <div class="popular-searches">
                <span class="trending-text">Trending:</span> 
                <div class="popular-tags">
                    <a href="<?php echo URL_ROOT; ?>/services/browse?search=website" class="tag">Web Development</a>
                    <a href="<?php echo URL_ROOT; ?>/services/browse?search=logo" class="tag">Logo Design</a>
                    <a href="<?php echo URL_ROOT; ?>/services/browse?search=mobile" class="tag">Mobile Apps</a>
                    <a href="<?php echo URL_ROOT; ?>/services/browse?search=ai" class="tag">AI Services</a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- CSS for Hero Section -->
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
    --primary: #2c3e50; /* Dark slate blue-gray, professional */
    --primary-light: #34495e;
    --primary-dark: #1a252f;
    --primary-accent: #ecf0f1;
    
    /* Secondary color palette - neutral and professional */
    --secondary: #222325; /* Dark gray for text */
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
    --shadow-sm: 0 2px 5px rgba(0,0,0,0.05);
    --shadow-md: 0 4px 12px rgba(0,0,0,0.05);
    --shadow-lg: 0 8px 24px rgba(0,0,0,0.08);
    --shadow-glow: 0 0 15px rgba(29, 191, 115, 0.3);
    --radius-sm: 4px;
    --radius-md: 8px;
    --radius-lg: 12px;
    --transition-fast: 0.2s ease;
    --transition-default: 0.3s ease;
    --container-max-width: 1280px;
    --container-padding: 32px;
    
    /* RGB values for opacity manipulations */
    --primary-rgb: 44, 62, 80;
    --secondary-rgb: 34, 35, 37;
    --accent-rgb: 116, 118, 126;
}

body {
    margin: 0;
    padding: 0;
    font-family: var(--font-primary);
    overflow-x: hidden; /* Prevent horizontal scrolling */
}

.modern-hero-section {
    position: relative;
    height: 720px; /* Increased height for more impact */
    overflow: hidden;
    color: var(--white);
    width: 100vw; /* Use viewport width instead of percentage */
    margin: 0;
    padding: 0;
    font-family: var(--font-primary);
    margin-top: -20px; /* Increased negative margin to eliminate gap */
    margin-left: -50vw; /* Extend beyond the container */
    margin-right: -50vw; /* Extend beyond the container */
    left: 50%; /* Center the element */
    right: 50%;
    box-sizing: border-box;
    position: relative;
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
    width: 100vw;
    height: 100%;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    object-fit: cover;
    filter: brightness(0.85); /* Slightly darker video for better text contrast */
}

.video-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(0,0,0,0.85) 0%, rgba(0,0,0,0.6) 50%, rgba(0,0,0,0.4) 100%);
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
    padding-left: calc(var(--container-padding) / 2);
    padding-right: var(--container-padding);
    margin: 0 auto; /* Changed to center the container */
    justify-content: flex-start;
    box-sizing: border-box;
}

.hero-content {
    max-width: 650px;
    text-align: left;
    padding-left: 0;
    animation: fadeInUp 1.2s ease-out;
}

@keyframes fadeInUp {
    from { opacity: 0; transform: translateY(40px); }
    to { opacity: 1; transform: translateY(0); }
}

.hero-title {
    font-size: 64px;
    font-weight: var(--font-weight-bold);
    margin-bottom: 20px;
    color: var(--white);
    line-height: 1.1;
    text-align: left;
    font-family: var(--font-primary);
    letter-spacing: -0.5px;
    opacity: 0;
    animation: fadeIn 0.8s 0.2s forwards;
}

@keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
}

.gradient-text {
    background: linear-gradient(to right, var(--primary-light), var(--secondary-light), var(--accent-purple));
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    display: inline-block;
    padding-right: 5px;
    position: relative;
    animation: gradientFlow 8s ease infinite;
    background-size: 200% auto;
}

@keyframes gradientFlow {
    0% { background-position: 0% center; }
    50% { background-position: 100% center; }
    100% { background-position: 0% center; }
}

.gradient-text::after {
    content: '';
    position: absolute;
    bottom: 5px;
    left: 0;
    width: 100%;
    height: 4px;
    background: linear-gradient(to right, var(--primary-light), var(--secondary-light), var(--accent-purple));
    border-radius: 10px;
    opacity: 0.5;
    animation: gradientFlow 8s ease infinite;
    background-size: 200% auto;
}

.hero-subtitle {
    font-size: 22px;
    font-weight: var(--font-weight-medium);
    margin-bottom: 36px;
    color: var(--white);
    opacity: 0;
    text-align: left;
    font-family: var(--font-primary);
    line-height: 1.4;
    animation: fadeIn 0.8s 0.4s forwards;
}

.hero-search-container {
    margin-bottom: 22px;
    width: 100%;
    text-align: left;
    position: relative;
    opacity: 0;
    animation: fadeIn 0.8s 0.6s forwards, floatUpDown 4s ease-in-out 1.5s infinite;
}

@keyframes floatUpDown {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-10px); }
}

.hero-search-form {
    display: flex;
    align-items: center;
    background-color: var(--white);
    border-radius: var(--radius-md);
    padding: 5px 8px;
    box-shadow: var(--shadow-lg), 0 0 0 5px rgba(255,255,255,0.1);
    position: relative;
    transition: all var(--transition-fast);
    overflow: hidden;
}

.hero-search-form:focus-within {
    transform: translateY(-2px);
    box-shadow: var(--shadow-glow), var(--shadow-lg), 0 0 0 5px rgba(255,255,255,0.2);
}

.hero-search-form::before {
    content: '';
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 3px;
    background: linear-gradient(to right, var(--primary), var(--secondary), var(--accent-purple));
    transform: scaleX(0);
    transform-origin: center;
    transition: transform 0.5s ease;
}

.hero-search-form:focus-within::before {
    transform: scaleX(1);
}

.search-icon {
    color: var(--primary);
    margin-left: 8px;
    margin-right: 8px;
    font-size: 18px;
    animation: pulse 2s infinite;
}

@keyframes pulse {
    0% { transform: scale(1); }
    50% { transform: scale(1.1); }
    100% { transform: scale(1); }
}

.hero-search-form input {
    flex: 1;
    border: none;
    padding: 16px 8px;
    font-size: 16px;
    font-family: var(--font-primary);
    outline: none;
    color: var(--text-dark);
}

.pulse-animation {
    animation: inputPulse 2s infinite alternate ease-in-out;
}

@keyframes inputPulse {
    0% { box-shadow: 0 0 0 0 rgba(16, 185, 129, 0); }
    100% { box-shadow: 0 0 0 8px rgba(16, 185, 129, 0); }
}

.search-button {
    background: linear-gradient(to right, var(--primary), var(--primary-dark));
    border: none;
    border-radius: var(--radius-md);
    color: var(--white);
    padding: 14px 28px;
    font-weight: var(--font-weight-medium);
    font-family: var(--font-primary);
    cursor: pointer;
    transition: all var(--transition-default);
    position: relative;
    overflow: hidden;
    font-size: 16px;
}

.search-button:hover {
    background: linear-gradient(to right, var(--primary-dark), var(--primary));
    transform: translateY(-2px);
}

.search-button:active {
    transform: translateY(1px);
}

.search-button::after {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    top: 0;
    left: -100%;
    background: linear-gradient(90deg, transparent, rgba(255,255,255,0.3), transparent);
    transition: 0.6s;
}

.search-button:hover::after {
    left: 100%;
}

.popular-searches {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 10px;
    color: rgba(255, 255, 255, 0.9);
    font-size: var(--font-size-base-sm);
    font-family: var(--font-primary);
    opacity: 0;
    animation: fadeIn 0.8s 0.8s forwards;
}

.trending-text {
    color: var(--primary-light);
    font-weight: var(--font-weight-medium);
}

.popular-tags {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
}

.tag {
    color: var(--white);
    text-decoration: none;
    padding: 5px 12px;
    border-radius: 20px;
    background-color: rgba(255, 255, 255, 0.15);
    transition: all var(--transition-fast);
    font-size: 13px;
    backdrop-filter: blur(4px);
    opacity: 0;
    animation: fadeInStaggered 0.5s forwards;
}

.tag:nth-child(1) { animation-delay: 1s; }
.tag:nth-child(2) { animation-delay: 1.1s; }
.tag:nth-child(3) { animation-delay: 1.2s; }
.tag:nth-child(4) { animation-delay: 1.3s; }

@keyframes fadeInStaggered {
    from { opacity: 0; transform: translateY(10px); }
    to { opacity: 1; transform: translateY(0); }
}

.tag:hover {
    background-color: rgba(255, 255, 255, 0.25);
    transform: translateY(-4px) scale(1.05);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
}

@media (max-width: 768px) {
    .modern-hero-section {
        height: 650px;
    }
    
    .hero-title {
        font-size: 42px;
    }
    
    .hero-subtitle {
        font-size: 18px;
        margin-bottom: 28px;
    }
    
    .hero-search-form {
        flex-direction: column;
        padding: 16px;
    }
    
    .hero-search-form input {
        width: 100%;
        margin-bottom: 12px;
        padding: 10px 0;
    }
    
    .search-button {
        width: 100%;
        padding: 12px;
    }
    
    .search-icon {
        display: none;
    }
    
    .popular-tags {
        margin-top: 5px;
    }
}

@media (max-width: 480px) {
    .hero-title {
        font-size: 36px;
    }
    
    .hero-subtitle {
        font-size: 16px;
    }
    
    .tag {
        padding: 4px 10px;
        font-size: 12px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Enhanced ripple effect for search button with larger spread
    const searchButton = document.querySelector('.search-button');
    
    searchButton.addEventListener('mousedown', function(e) {
        const button = e.currentTarget;
        const circle = document.createElement('span');
        const diameter = Math.max(button.clientWidth, button.clientHeight) * 2.5;
        
        circle.style.width = circle.style.height = `${diameter}px`;
        circle.style.left = `${e.clientX - button.offsetLeft - diameter/2}px`;
        circle.style.top = `${e.clientY - button.offsetTop - diameter/2}px`;
        circle.classList.add('ripple');
        
        const ripple = button.getElementsByClassName('ripple')[0];
        if (ripple) {
            ripple.remove();
        }
        
        button.appendChild(circle);
    });
    
    // Remove pulse animation when input is focused
    const searchInput = document.querySelector('.hero-search-form input');
    searchInput.addEventListener('focus', function() {
        this.classList.remove('pulse-animation');
    });
    
    // Subtle parallax effect on the video background
    const videoBackground = document.querySelector('.video-background');
    const heroContent = document.querySelector('.hero-content');
    
    window.addEventListener('scroll', function() {
        const scrollValue = window.scrollY;
        
        if (videoBackground && scrollValue < 800) {
            videoBackground.style.transform = `translateY(${scrollValue * 0.3}px)`;
        }
        
        if (heroContent && scrollValue < 800) {
            heroContent.style.transform = `translateY(${scrollValue * 0.15}px)`;
            heroContent.style.opacity = 1 - (scrollValue * 0.002);
        }
    });
    
    // Dynamic text effect for hero title
    const gradientText = document.querySelector('.gradient-text');
    if (gradientText) {
        setInterval(() => {
            gradientText.style.filter = `hue-rotate(${Math.random() * 30}deg)`;
        }, 2000);
    }
});
</script>

<style>
/* Additional styles for interactive elements */
.ripple {
    position: absolute;
    border-radius: 50%;
    background-color: rgba(255, 255, 255, 0.6);
    transform: scale(0);
    animation: ripple 0.8s cubic-bezier(0.1, 0.1, 0.25, 1);
    pointer-events: none;
}

@keyframes ripple {
    to {
        transform: scale(2);
        opacity: 0;
    }
}

/* Smooth scroll behavior for the entire site */
html {
    scroll-behavior: smooth;
}
</style>

<!-- CSS for modern interactive cursor effect -->
<style>
/* Interactive cursor effect */
.mouse-effect {
    position: fixed;
    pointer-events: none;
    z-index: 9999;
}

.circle {
    position: fixed;
    transform: translate(-50%, -50%);
    width: 12px;
    height: 12px;
    border-radius: 50%;
    background-color: var(--primary);
    transition: width 0.3s, height 0.3s, background-color 0.3s;
    pointer-events: none;
    opacity: 0.7;
    z-index: 9999;
}


/* Active states for cursor */
.clickable-hover .circle {
    background-color: var(--primary-light);
    transform: translate(-50%, -50%) scale(1.5);
}

.clickable-hover .circle-follow {
    background-color: rgba(16, 185, 129, 0.1);
    transform: translate(-50%, -50%) scale(1.2);
}
</style>

<!-- Categories Section - Modern layout with professional design -->
<section class="category-section">
    <div class="container">
        <h2 class="section-title reveal fade-up">Explore Popular Categories</h2>
        <p class="section-subtitle reveal fade-up">Discover services across various categories</p>
        
        <div class="fiverr-categories-container">
            <a href="<?php echo URL_ROOT; ?>/services/browse?category=programming" class="fiverr-category-card reveal fade-up" data-delay="100">
                <div class="category-icon">
                    <i class="fas fa-code"></i>
                </div>
                <span class="category-name">Programming & Tech</span>
                <div class="card-overlay"></div>
            </a>
            
            <a href="<?php echo URL_ROOT; ?>/services/browse?category=design" class="fiverr-category-card reveal fade-up" data-delay="200">
                <div class="category-icon">
                    <i class="fas fa-palette"></i>
                </div>
                <span class="category-name">Graphics & Design</span>
                <div class="card-overlay"></div>
            </a>
            
            <a href="<?php echo URL_ROOT; ?>/services/browse?category=digital-marketing" class="fiverr-category-card reveal fade-up" data-delay="300">
                <div class="category-icon">
                    <i class="fas fa-bullhorn"></i>
                </div>
                <span class="category-name">Digital Marketing</span>
                <div class="card-overlay"></div>
            </a>
            
            <a href="<?php echo URL_ROOT; ?>/services/browse?category=writing" class="fiverr-category-card reveal fade-up" data-delay="400">
                <div class="category-icon">
                    <i class="fas fa-pen-fancy"></i>
                </div>
                <span class="category-name">Writing & Translation</span>
                <div class="card-overlay"></div>
            </a>
            
            <a href="<?php echo URL_ROOT; ?>/services/browse?category=video" class="fiverr-category-card reveal fade-up" data-delay="500">
                <div class="category-icon">
                    <i class="fas fa-film"></i>
                </div>
                <span class="category-name">Video & Animation</span>
                <div class="card-overlay"></div>
            </a>
            
            <a href="<?php echo URL_ROOT; ?>/services/browse?category=music" class="fiverr-category-card reveal fade-up" data-delay="600">
                <div class="category-icon">
                    <i class="fas fa-music"></i>
                </div>
                <span class="category-name">Music & Audio</span>
                <div class="card-overlay"></div>
            </a>
            
            <a href="<?php echo URL_ROOT; ?>/services/browse?category=business" class="fiverr-category-card reveal fade-up" data-delay="700">
                <div class="category-icon">
                    <i class="fas fa-chart-line"></i>
                </div>
                <span class="category-name">Business</span>
                <div class="card-overlay"></div>
            </a>
            
            <a href="<?php echo URL_ROOT; ?>/services/browse?category=ai-services" class="fiverr-category-card reveal fade-up" data-delay="800">
                <div class="category-icon">
                    <i class="fas fa-robot"></i>
                </div>
                <span class="category-name">AI Services</span>
                <div class="card-overlay"></div>
            </a>
        </div>
    </div>
</section>

<!-- CSS for Categories Section -->
<style>
/* Professional Category Section Styling - Aligned with site theme */
.category-section {
    padding: 90px 0;
    background-color: #f9fafc;
    position: relative;
    overflow: hidden;
}

.category-section::before {
    content: '';
    position: absolute;
    width: 350px;
    height: 350px;
    border-radius: 50%;
    background: linear-gradient(135deg, rgba(44, 62, 80, 0.05) 0%, rgba(34, 35, 37, 0.05) 100%);
    top: -180px;
    left: -180px;
    z-index: 0;
}

.category-section::after {
    content: '';
    position: absolute;
    width: 300px;
    height: 300px;
    border-radius: 50%;
    background: linear-gradient(135deg, rgba(44, 62, 80, 0.05) 0%, rgba(116, 118, 126, 0.05) 100%);
    bottom: -120px;
    right: -120px;
    z-index: 0;
}

.section-title {
    font-size: 42px;
    font-weight: var(--font-weight-bold);
    color: var(--text-dark);
    text-align: center;
    margin-bottom: 16px;
    position: relative;
    z-index: 1;
}

.section-subtitle {
    font-size: 18px;
    color: var(--gray-medium);
    text-align: center;
    margin-bottom: 48px;
    position: relative;
    z-index: 1;
    max-width: 600px;
    margin-left: auto;
    margin-right: auto;
}

.fiverr-categories-container {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
    gap: 30px;
    margin: 0 auto;
    position: relative;
    z-index: 1;
}

.fiverr-category-card {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-decoration: none;
    padding: 35px 25px;
    border-radius: var(--radius-md);
    background-color: var(--white);
    transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
    border: 1px solid rgba(229, 231, 235, 0.7);
    text-align: center;
    height: 100%;
    position: relative;
    overflow: hidden;
}

.card-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(44, 62, 80, 0.03) 0%, rgba(34, 35, 37, 0.03) 100%);
    opacity: 0;
    transition: opacity 0.4s ease;
    z-index: -1;
}

.fiverr-category-card:hover {
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    transform: translateY(-8px);
    border-color: rgba(44, 62, 80, 0.15);
}

.fiverr-category-card:hover .card-overlay {
    opacity: 1;
}

.category-icon {
    margin-bottom: 24px;
    width: 80px;
    height: 80px;
    background-color: #f5f7fa;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
    position: relative;
    z-index: 1;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.05);
}

.category-icon::before {
    content: '';
    position: absolute;
    width: 100%;
    height: 100%;
    border-radius: var(--radius-md);
    background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
    opacity: 0;
    transition: opacity 0.4s ease;
    z-index: -1;
}

.category-icon i {
    font-size: 30px;
    color: var(--primary);
    transition: all 0.4s cubic-bezier(0.25, 0.46, 0.45, 0.94);
}

.fiverr-category-card:hover .category-icon {
    transform: scale(1.05);
    background-color: transparent;
}

.fiverr-category-card:hover .category-icon::before {
    opacity: 1;
}

.fiverr-category-card:hover .category-icon i {
    color: var(--white);
}

.category-name {
    font-size: 17px;
    font-weight: var(--font-weight-medium);
    color: var(--text-dark);
    line-height: 1.4;
    transition: all 0.3s ease;
    position: relative;
}

.category-name::after {
    content: '';
    position: absolute;
    bottom: -8px;
    left: 50%;
    width: 0;
    height: 2px;
    background-color: var(--primary);
    transition: all 0.3s ease;
    transform: translateX(-50%);
}

.fiverr-category-card:hover .category-name {
    color: var(--primary-dark);
}

.fiverr-category-card:hover .category-name::after {
    width: 40px;
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

.fade-up {
    transition-property: transform, opacity;
}

.reveal[data-delay="100"] { transition-delay: 0.1s; }
.reveal[data-delay="200"] { transition-delay: 0.2s; }
.reveal[data-delay="300"] { transition-delay: 0.3s; }
.reveal[data-delay="400"] { transition-delay: 0.4s; }
.reveal[data-delay="500"] { transition-delay: 0.5s; }
.reveal[data-delay="600"] { transition-delay: 0.6s; }
.reveal[data-delay="700"] { transition-delay: 0.7s; }
.reveal[data-delay="800"] { transition-delay: 0.8s; }

@media (max-width: 992px) {
    .fiverr-categories-container {
        grid-template-columns: repeat(auto-fill, minmax(190px, 1fr));
        gap: 25px;
    }
    
    .section-title {
        font-size: 36px;
    }
    
    .fiverr-category-card {
        padding: 30px 20px;
    }
    
    .category-icon {
        width: 70px;
        height: 70px;
        margin-bottom: 20px;
    }
}

@media (max-width: 768px) {
    .fiverr-categories-container {
        grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
        gap: 20px;
    }
    
    .category-icon {
        width: 65px;
        height: 65px;
        margin-bottom: 18px;
    }
    
    .category-icon i {
        font-size: 24px;
    }
    
    .section-title {
        font-size: 32px;
    }
    
    .section-subtitle {
        font-size: 16px;
        margin-bottom: 35px;
    }
    
    .fiverr-category-card {
        padding: 25px 15px;
    }
}

@media (max-width: 480px) {
    .fiverr-categories-container {
        grid-template-columns: repeat(2, 1fr);
        gap: 15px;
    }
    
    .fiverr-category-card {
        padding: 20px 15px;
    }
    
    .category-icon {
        width: 55px;
        height: 55px;
        margin-bottom: 15px;
    }
    
    .category-icon i {
        font-size: 22px;
    }
    
    .category-name {
        font-size: 15px;
    }
    
    .section-title {
        font-size: 28px;
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
    
    // Enhanced hover effects for category cards
    const categoryCards = document.querySelectorAll('.fiverr-category-card');
    
    categoryCards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            // Subtle animation for the whole card
            gsap.to(this, {
                duration: 0.4,
                y: -8,
                boxShadow: "0 15px 30px rgba(0, 0, 0, 0.12)",
                ease: "power2.out"
            });
            
            // Animate the icon
            const categoryIcon = this.querySelector('.category-icon');
            if (categoryIcon) {
                gsap.to(categoryIcon, {
                    duration: 0.4,
                    scale: 1.05,
                    ease: "back.out(1.5)"
                });
            }
            
            // Animate the name underline
            const categoryName = this.querySelector('.category-name');
            if (categoryName) {
                gsap.to(categoryName, {
                    duration: 0.3,
                    color: getComputedStyle(document.documentElement).getPropertyValue('--primary-dark'),
                    ease: "power1.out"
                });
                
                const nameAfter = categoryName.querySelector('::after');
                if (nameAfter) {
                    gsap.to(nameAfter, {
                        duration: 0.4,
                        width: 40,
                        ease: "power1.out"
                    });
                }
            }
        });
        
        card.addEventListener('mouseleave', function() {
            // Reset animations
            gsap.to(this, {
                duration: 0.4,
                y: 0,
                boxShadow: "0 4px 12px rgba(0, 0, 0, 0.06)",
                ease: "power2.out"
            });
            
            const categoryIcon = this.querySelector('.category-icon');
            if (categoryIcon) {
                gsap.to(categoryIcon, {
                    duration: 0.4,
                    scale: 1,
                    ease: "power2.out"
                });
            }
            
            const categoryName = this.querySelector('.category-name');
            if (categoryName) {
                gsap.to(categoryName, {
                    duration: 0.3,
                    color: getComputedStyle(document.documentElement).getPropertyValue('--text-dark'),
                    ease: "power1.out"
                });
            }
        });
    });
});
</script>

<style>
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
    transition: transform 0.4s ease;
}

.fiverr-category-card:hover::after {
    transform: scaleX(1);
}

.fiverr-category-card::before {
    content: '';
    position: absolute;
    bottom: 12px;
    right: 12px;
    width: 10px;
    height: 10px;
    border-right: 2px solid var(--primary);
    border-bottom: 2px solid var(--primary);
    opacity: 0;
    transition: all 0.3s ease;
}

.fiverr-category-card:hover::before {
    opacity: 0.7;
}
</style>

<!-- Modern Testimonials & Reviews Section -->
<section class="testimonials-section">
    <div class="container">
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
        color: var(--primary-color);
        opacity: 0.6;
        transition: opacity 0.7s cubic-bezier(0.6, 0.05, 0, 1);
    }
    
    .testimonial-grid svg {
        width: 18px;
        color: var(--primary-color);
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
        color: var(--primary-color);
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
<section class="user-path-section">
    <div class="container">
        <h2 class="section-title reveal fade-up">Join Our Marketplace</h2>
        <p class="section-subtitle reveal fade-up">Choose your path and start your journey today</p>
        
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
                        <img src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1471&q=80" loading="lazy" class="tab-image">
                    </div>
                    <div data-tabs="visual-item" class="tab-visual-item">
                        <img src="https://images.unsplash.com/photo-1543269865-cbf427effbad?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" loading="lazy" class="tab-image">
                    </div>
                    <div data-tabs="visual-item" class="tab-visual-item">
                        <img src="https://images.unsplash.com/photo-1542744173-8e7e53415bb0?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=1470&q=80" loading="lazy" class="tab-image">
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
    transition: background-color 0.3s ease, border-color 0.3s ease;
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
}

.tab-content-item {
    z-index: 1;
    grid-column-gap: 1.25em;
    grid-row-gap: 1.25em;
    visibility: hidden;
    flex-flow: column;
    display: flex;
    position: absolute;
    inset: auto 0% 0%;
}

.tab-content-item.active {
    visibility: visible;
}

.tab-content__heading {
    letter-spacing: -0.02em;
    margin-top: 0;
    margin-bottom: 0.5em;
    font-size: 1.75em;
    font-weight: var(--font-weight-bold);
    line-height: 1.2;
    color: var(--primary);
}

.content-p {
    margin: 0;
    font-size: 1.25em;
    line-height: 1.5;
    color: var(--text-dark);
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
    justify-content: flex-start;
    align-items: center;
    width: 100%;
    height: 100%;
    display: flex;
    position: absolute;
}

.tab-visual-item.active {
    visibility: visible;
}

.tab-image {
    object-fit: cover;
    border-radius: var(--radius-lg);
    width: 100%;
    max-width: none;
    height: 100%;
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
    // Initialize GSAP if not already initialized
    if (typeof gsap === 'undefined') {
        console.warn('GSAP not loaded. Tab functionality might be limited.');
        return;
    }
    
    // Register plugins if available
    if (gsap.registerPlugin && typeof CustomEase !== 'undefined' && typeof Flip !== 'undefined') {
        gsap.registerPlugin(CustomEase, Flip);
        CustomEase.create("osmo-ease", "0.625, 0.05, 0, 1");
        
        gsap.defaults({
            ease: "osmo-ease",
            duration: 0.8,
        });
    }
    
    // Initialize Flip buttons functionality
    initFlipButtons();
    
    // Initialize Tab System
    initTabSystem();
    
    function initFlipButtons() {
        let wrappers = document.querySelectorAll('[data-flip-button="wrap"]');
        
        wrappers.forEach((wrapper) => {
            let buttons = wrapper.querySelectorAll('[data-flip-button="button"]');
            let bg = wrapper.querySelector('[data-flip-button="bg"]');
            
            if (!bg) return;
            
            buttons.forEach(function (button) {
                // Handle mouse enter
                button.addEventListener("mouseenter", function () {
                    if (typeof Flip !== 'undefined') {
                        const state = Flip.getState(bg);
                        this.appendChild(bg);
                        Flip.from(state, {
                            duration: 0.4,
                        });
                    } else {
                        this.appendChild(bg);
                    }
                });

                // Handle focus for keyboard navigation
                button.addEventListener("focus", function () {
                    if (typeof Flip !== 'undefined') {
                        const state = Flip.getState(bg);
                        this.appendChild(bg);
                        Flip.from(state, {
                            duration: 0.4,
                        });
                    } else {
                        this.appendChild(bg);
                    }
                });

                // Handle mouse leave
                button.addEventListener("mouseleave", function () {
                    const activeLink = wrapper.querySelector(".active");
                    if (!activeLink) return;
                    
                    if (typeof Flip !== 'undefined') {
                        const state = Flip.getState(bg);
                        activeLink.appendChild(bg);
                        Flip.from(state, {
                            duration: 0.4,
                        });
                    } else {
                        activeLink.appendChild(bg);
                    }
                });

                // Handle blur for keyboard navigation
                button.addEventListener("blur", function () {
                    const activeLink = wrapper.querySelector(".active");
                    if (!activeLink) return;
                    
                    if (typeof Flip !== 'undefined') {
                        const state = Flip.getState(bg);
                        activeLink.appendChild(bg);
                        Flip.from(state, {
                            duration: 0.4,
                        });
                    } else {
                        activeLink.appendChild(bg);
                    }
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

                let outgoingLines = outgoingContent.querySelectorAll("[data-tabs-fade]") || [];
                let incomingLines = incomingContent.querySelectorAll("[data-tabs-fade]");

                const timeline = gsap.timeline({
                    defaults: {
                        ease: "power3.inOut"
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

                incomingContent.classList.add("active");
                if (incomingVisual) incomingVisual.classList.add("active");

                timeline
                    .to(outgoingLines, { y: "-2em", autoAlpha: 0 }, 0)
                    .to(outgoingVisual, { autoAlpha: 0, xPercent: 3 }, 0)
                    .fromTo(incomingLines, { y: "2em", autoAlpha: 0 }, { y: "0em", autoAlpha: 1, stagger: 0.075 }, 0.4)
                    .fromTo(incomingVisual, { autoAlpha: 0, xPercent: 3 }, { autoAlpha: 1, xPercent: 0 }, "<");

                activeButton && activeButton.classList.remove("active");
                buttons[index].classList.add("active");
                activeButton = buttons[index];
            }

            switchTab(0, true); // Initialize on page load
         
            buttons.forEach((button, i) => {
                button.addEventListener("click", () => switchTab(i)); 
            });
        });
    }
    
    // Custom mouse effect
    initCustomCursor();
    
    function initCustomCursor() {
        const circle = document.querySelector('.circle');
        const circleFollow = document.querySelector('.circle-follow');
        
        if (!circle || !circleFollow) return;
        
        window.addEventListener('mousemove', (e) => {
            circle.style.left = e.clientX + 'px';
            circle.style.top = e.clientY + 'px';
            
            // Use GSAP for smoother following effect if available
            if (typeof gsap !== 'undefined') {
                gsap.to(circleFollow, { 
                    duration: 0.3, 
                    left: e.clientX,
                    top: e.clientY
                });
            } else {
                // Fallback for browsers without GSAP
                circleFollow.style.left = e.clientX + 'px';
                circleFollow.style.top = e.clientY + 'px';
            }
        });
        
        // Add hover effect for clickable elements
        const clickableElements = document.querySelectorAll('a, button, input, .fiverr-category-card, .tag, .testimonial-indicator');
        
        clickableElements.forEach(element => {
            element.addEventListener('mouseenter', () => {
                document.body.classList.add('clickable-hover');
            });
            
            element.addEventListener('mouseleave', () => {
                document.body.classList.remove('clickable-hover');
            });
        });
    }
});
</script>

<!-- CTA Section - Updated with modern design and image background -->
<div class="cta-wrapper">
    <div class="cta-container">
        <h2 class="cta-heading">Freelance services at your fingertips</h2>
        <div class="cta-buttons">
            <a href="<?php echo URL_ROOT; ?>/users/register" class="cta-button primary">Join LenSi</a>
        </div>
    </div>
</div>

<!-- CSS for CTA Section -->
<style>
.cta-wrapper {
    width: 100%;
    padding: 70px 0;
    box-sizing: border-box;
    background-color: var(--white);
}

.cta-container {
    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;
    text-align: center;
    padding: 70px 30px;
    border-radius: var(--radius-lg);
    position: relative;
    box-shadow: var(--shadow-lg);
    max-width: var(--container-max-width);
    margin: 0 auto;
    background-image: url('https://fiverr-res.cloudinary.com/image/upload/f_auto,q_auto/v1/attachments/generic_asset/asset/6b00c32b725da4ab7e56838a5f50134e-1743599491289/Background%20_%20Go%20section.png');
    background-size: cover;
    background-position: top right;
    overflow: hidden;
    position: relative;
    box-sizing: border-box;
    min-height: 436px;
    width: 100%;
    
    /* Set a fixed height for larger screens */
    @media screen and (min-width: 900px) {
        min-height: 500px;
        background-position: 85% 15%;
    }
}

.cta-heading {
    text-align: center;
    font-size: 54px; /* Increased size for impact */
    line-height: 1.2;
    font-weight: var(--font-weight-bold);
    margin-bottom: 30px;
    width: 100%; /* Full container width */
    position: relative;
    z-index: 2;
    background: linear-gradient(90deg, #ffffff 0%, #aedaff 50%, #ffffff 100%);
    -webkit-background-clip: text;
    background-clip: text;
    color: transparent;
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.cta-buttons {
    position: relative;
    z-index: 2;
}

.cta-button {
    display: inline-block;
    padding: 16px 40px;
    border: none;
    border-radius: 8px; /* Smaller border radius */
    font-weight: var(--font-weight-bold);
    font-size: 16px;
    line-height: 24px;
    text-decoration: none;
    transition: background-color 0.3s ease;
    position: relative;
}

.cta-button.primary {
    background: white; /* White background```php
    background: white; /* White background instead of orange */
    color: #FF8C66; /* Orange text color */
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    border: none;
}

.cta-button.primary:hover {
    background: #f8f8f8; /* Slightly off-white on hover */
}

.cta-button.primary:focus {
    outline: none;
    background: #f8f8f8;
    box-shadow: 0 4px 12px rgba(0, 0.15);
}

@media screen and (max-width: 768px) {
    .cta-heading {
        font-size: 36px; /* Smaller size for mobile */
    }
    
    .cta-container {
        padding: 50px 20px;
    }
    
    .cta-button.primary {
        padding: 14px 32px;
    }
}
</style>
