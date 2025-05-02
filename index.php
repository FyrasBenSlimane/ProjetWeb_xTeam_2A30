<?php
// Set up basic error reporting to debug component loading issues
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define the components with absolute paths
$componentsPath = __DIR__ . '/components';
$components = [
    'navbar' => $componentsPath . '/navbar.php',
    'hero' => $componentsPath . '/hero.php',
    'content-sections' => $componentsPath . '/content-sections.php',
    'footer' => $componentsPath . '/footer.php',
    'auth' => $componentsPath . '/auth.php'
];

// Checking component paths before loading
foreach ($components as $name => $path) {
    if (!file_exists($path)) {
        echo "<!-- Warning: Component '$name' not found at path: $path -->";
    }
}
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="LenSi - Connect with talented freelancers for your business needs">
    <meta name="theme-color" content="#3E5C76">
    <title>LenSi - Freelance Marketplace</title>
    
    <!-- Preload des ressources critiques -->
    <link rel="preload" href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Inter:wght@300;400;500&family=Poppins:wght@400;500;600;700&display=swap" as="style">
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" as="style">
    <link rel="preload" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css" as="style">
    
    <!-- Chargement des styles -->
    <link rel="icon" type="image/svg+xml" href="assets/images/logo_white.svg" sizes="any">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Inter:wght@300;400;500&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <style>
    /* Root CSS Variables and Global Styles */
    :root {
        --primary: #3E5C76;
        --primary-rgb: 62, 92, 118;
        --secondary: #748CAB;
        --accent: #1D2D44;
        --accent-dark: #0D1B2A;
        --light: #F9F7F0;
        --dark: #0D1B2A;
        --font-primary: 'Montserrat', sans-serif;
        --font-secondary: 'Inter', sans-serif;
        --font-heading: 'Poppins', sans-serif;
        --transition-default: all 0.3s ease;
        --transition-bounce: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
        --spacing-xs: 0.5rem;
        --spacing-sm: 1rem;
        --spacing-md: 1.5rem;
        --spacing-lg: 2rem;
        --spacing-xl: 3rem;
        --border-radius-sm: 0.25rem;
        --border-radius-md: 0.5rem;
        --border-radius-lg: 1rem;
        --container-width: 1400px;
        --header-height: 80px;
        --text-xs: 0.75rem;
        --text-sm: 0.875rem;
        --text-md: 1rem;
        --text-lg: 1.25rem;
        --text-xl: 1.5rem;
        --text-2xl: 2rem;
        --text-3xl: 2.5rem;
        --text-4xl: 3rem;
    }

    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    html {
        font-size: 16px;
        scroll-behavior: smooth;
    }

    body {
        font-family: var(--font-secondary);
        line-height: 1.6;
        color: var(--accent);
        background-color: var(--light);
        min-height: 100vh;
        transition: background-color 0.3s ease;
        padding-bottom: 0;
        overflow-x: hidden;
    }

    [data-bs-theme="dark"] {
        --light: #121212;
        --dark: #F9F7F0;
        --accent: #A4C2E5;
        --accent-dark: #171821;
        --primary: #5D8BB3;
        --primary-rgb: 93, 139, 179;
        --secondary: #8FB3DE;
        color: var(--accent);
        background-color: var(--light);
    }

    h1, h2, h3, h4, h5, h6 {
        font-family: var(--font-primary);
        font-weight: 600;
        color: var(--accent);
        line-height: 1.2;
    }

    h1 {
        font-size: var(--text-4xl);
    }

    h2 {
        font-size: var(--text-3xl);
        margin-bottom: 1.5rem;
    }

    h3 {
        font-size: var(--text-2xl);
    }

    h4 {
        font-size: var(--text-xl);
    }

    p {
        margin-bottom: 1rem;
    }

    img {
        max-width: 100%;
        height: auto;
    }

    .container {
        max-width: var(--container-width);
        width: 100%;
        padding: 0 var(--spacing-sm);
        margin: var(--spacing-xl) auto;
    }

    .btn {
        transition: var(--transition-default);
    }

    /* Enhanced Responsive Typography */
    @media (max-width: 1400px) {
        :root {
            --container-width: 1140px;
        }
    }

    @media (max-width: 1200px) {
        :root {
            --container-width: 960px;
            --text-4xl: 2.75rem;
            --text-3xl: 2.25rem;
            --text-2xl: 1.75rem;
        }
    }

    @media (max-width: 992px) {
        :root {
            --container-width: 720px;
            --text-4xl: 2.5rem;
            --text-3xl: 2rem;
            --text-2xl: 1.5rem;
            --text-xl: 1.3rem;
        }
        
        .container {
            padding: 0 var(--spacing-md);
        }
    }

    @media (max-width: 768px) {
        :root {
            --container-width: 540px;
            --text-4xl: 2.25rem;
            --text-3xl: 1.75rem;
            --text-2xl: 1.4rem;
            --text-xl: 1.25rem;
            --text-lg: 1.125rem;
            --spacing-xl: 2rem;
        }
        
        html {
            font-size: 15px;
        }
        
        h2 {
            margin-bottom: 1.25rem;
        }
        
        .container {
            margin: var(--spacing-lg) auto;
        }
    }

    @media (max-width: 576px) {
        :root {
            --container-width: 100%;
            --text-4xl: 2rem;
            --text-3xl: 1.5rem;
            --text-2xl: 1.3rem;
            --text-xl: 1.15rem;
            --spacing-xl: 1.5rem;
            --spacing-lg: 1.25rem;
        }
        
        html {
            font-size: 14px;
        }
        
        .container {
            padding: 0 var(--spacing-sm);
            margin: var(--spacing-md) auto;
        }
    }

    /* Utility classes for responsiveness */
    .d-sm-none {
        display: block;
    }
    
    .d-sm-block {
        display: none;
    }
    
    @media (max-width: 768px) {
        .d-sm-none {
            display: none !important;
        }
        
        .d-sm-block {
            display: block !important;
        }
    }
    
    .text-center-sm {
        text-align: inherit;
    }
    
    @media (max-width: 768px) {
        .text-center-sm {
            text-align: center !important;
        }
    }
    </style>
</head>
<body>
    <!-- Navbar Component -->
    <?php if (file_exists($components['navbar'])): ?>
        <?php include $components['navbar']; ?>
    <?php else: ?>
        <div class="alert alert-danger m-3">Error loading navbar component: File not found at <?= $components['navbar'] ?></div>
    <?php endif; ?>
    
    <!-- hero Component -->
    <?php if (file_exists($components['hero'])): ?>
        <?php include $components['hero']; ?>
    <?php else: ?>
        <div class="alert alert-danger m-3">Error loading hero component: File not found at <?= $components['hero'] ?></div>
    <?php endif; ?>
    
    <!-- Content Sections Component -->
    <?php if (file_exists($components['content-sections'])): ?>
        <?php include $components['content-sections']; ?>
    <?php else: ?>
        <div class="alert alert-danger m-3">Error loading content sections component: File not found at <?= $components['content-sections'] ?></div>
    <?php endif; ?>

    <!-- Events Component -->
    <?php include 'components/events.php'; ?>
    
    <!-- Footer Component -->
    <?php if (file_exists($components['footer'])): ?>
        <?php include $components['footer']; ?>
    <?php else: ?>
        <div class="alert alert-danger m-3">Error loading footer component: File not found at <?= $components['footer'] ?></div>
    <?php endif; ?>
    
    <!-- Auth Overlay Component -->
    <?php if (file_exists($components['auth'])): ?>
        <?php include $components['auth']; ?>
    <?php else: ?>
        <div class="alert alert-danger m-3">Error loading auth component: File not found at <?= $components['auth'] ?></div>
    <?php endif; ?>
    
    <!-- Hidden elements for theme transition -->
    <div id="themeTransition" style="display:none;">
        <span id="year" style="display: none;"></span>
    </div>
    
    <!-- Single JavaScript file for the entire site -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
    <script>
    // Initialize theme based on preferences on page load
    document.addEventListener('DOMContentLoaded', function() {
        // Get saved theme or use system preference
        const savedTheme = localStorage.getItem('theme');
        const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');
        const themeToUse = savedTheme || (prefersDarkScheme.matches ? 'dark' : 'light');
        
        // Set the initial theme
        document.documentElement.setAttribute('data-bs-theme', themeToUse);
        
        // Trigger theme changed event for script.js
        document.dispatchEvent(new CustomEvent('themeChanged', { 
            detail: { theme: themeToUse }
        }));
    });
    
    // Listen for system preference changes
    window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', function(e) {
        // Only change theme if user hasn't set a preference
        if (!localStorage.getItem('theme')) {
            const newTheme = e.matches ? 'dark' : 'light';
            document.documentElement.setAttribute('data-bs-theme', newTheme);
            
            // Trigger theme changed event
            document.dispatchEvent(new CustomEvent('themeChanged', { 
                detail: { theme: newTheme }
            }));
        }
    });
    
    // Navbar scroll behavior
    const navbar = document.querySelector('.navbar');
    if (navbar) {
        window.addEventListener('DOMContentLoaded', function() {
            setTimeout(function() {
                navbar.classList.add('visible');
            }, 500);
        });
        
        window.addEventListener('scroll', function() {
            if (window.scrollY > 100) {
                navbar.classList.add('scrolled');
            } else {
                navbar.classList.remove('scrolled');
            }
        });
    }
    
    // Section animations on scroll
    const sections = document.querySelectorAll('.section-animate');
    if (sections.length > 0) {
        // Function to check if an element is in viewport
        function isInViewport(element) {
            const rect = element.getBoundingClientRect();
            return (
                rect.top <= (window.innerHeight || document.documentElement.clientHeight) * 0.75
            );
        }
        
        // Function to handle scroll animations
        function handleScrollAnimations() {
            sections.forEach(section => {
                if (isInViewport(section)) {
                    section.classList.add('visible');
                }
            });
        }
        
        // Initial check for elements in viewport
        window.addEventListener('DOMContentLoaded', function() {
            handleScrollAnimations();
        });
        
        // Check for elements in viewport on scroll
        window.addEventListener('scroll', function() {
            handleScrollAnimations();
        });
    }
    
    // Authentication overlay functionality
    const loginBtn = document.getElementById('loginBtn');
    const authOverlay = document.getElementById('authOverlay');
    const authContainer = document.getElementById('authContainer');
    const closeAuth = document.getElementById('closeAuth');
    const loginToggle = document.getElementById('loginToggle');
    const registerToggle = document.getElementById('registerToggle');
    const becomeSellerBtn = document.getElementById('becomeSellerBtn');
    
    // Only set up auth functionality if elements exist
    if (authOverlay && authContainer) {
        function openAuthOverlay(isRegistration = false) {
            authOverlay.classList.add('active');
            document.body.style.overflow = 'hidden';
            
            if (isRegistration) {
                authContainer.classList.add('active');
            } else {
                authContainer.classList.remove('active');
            }
        }
        
        function closeAuthOverlay() {
            authOverlay.classList.remove('active');
            document.body.style.overflow = 'auto';
        }
        
        if (loginBtn) {
            loginBtn.addEventListener('click', function() {
                openAuthOverlay(false);
            });
        }
        
        if (becomeSellerBtn) {
            becomeSellerBtn.addEventListener('click', function() {
                openAuthOverlay(true);
            });
        }
        
        if (closeAuth) {
            closeAuth.addEventListener('click', closeAuthOverlay);
        }
        
        if (loginToggle) {
            loginToggle.addEventListener('click', function() {
                authContainer.classList.remove('active');
            });
        }
        
        if (registerToggle) {
            registerToggle.addEventListener('click', function() {
                authContainer.classList.add('active');
            });
        }
        
        authOverlay.addEventListener('click', function(e) {
            if (e.target === authOverlay) {
                closeAuthOverlay();
            }
        });
    }
    
    // Create bubbles in the hero background
    function createBubbles() {
        const heroBackground = document.querySelector('.hero-background');
        if (!heroBackground) return;
        
        const bubbleCount = 5;
        
        // Remove existing bubbles (if recreating)
        const existingBubbles = document.querySelectorAll('.bubble:not([style])');
        existingBubbles.forEach(bubble => bubble.remove());
        
        for (let i = 0; i < bubbleCount; i++) {
            const bubble = document.createElement('div');
            bubble.classList.add('bubble');
            
            // Random size between 100px and 300px
            const size = Math.floor(Math.random() * 200) + 100;
            
            // Random position
            const top = Math.floor(Math.random() * 80) + 10; // 10-90%
            const left = Math.floor(Math.random() * 80) + 10; // 10-90%
            
            // Random opacity
            const opacity = (Math.random() * 0.4) + 0.2; // 0.2-0.6
            
            // Set styles
            bubble.style.width = `${size}px`;
            bubble.style.height = `${size}px`;
            bubble.style.top = `${top}%`;
            bubble.style.left = `${left}%`;
            bubble.style.opacity = opacity;
            
            // Random animation delay
            bubble.style.animationDelay = `${Math.random() * 5}s`;
            
            // Add to hero background
            heroBackground.appendChild(bubble);
        }
    }
    
    // Create bubbles when page loads
    window.addEventListener('DOMContentLoaded', createBubbles);
    </script>
</body>
</html>
