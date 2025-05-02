document.addEventListener('DOMContentLoaded', () => {
    // Check if user is logged in
    try {
        checkUserLoginStatus();
    } catch(e) {
        console.log('User login status check error:', e);
    }

    // Improved Theme toggle functionality
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        // Handle logo transition on theme change
        document.addEventListener('themeChanged', (event) => {
            const newTheme = event.detail.theme;
            const lightLogo = document.querySelector('.logo-light');
            const darkLogo = document.querySelector('.logo-dark');
            
            if (lightLogo && darkLogo) {
                if (newTheme === 'light') {
                    // Switch to light theme logo
                    darkLogo.style.opacity = '1';
                    lightLogo.style.opacity = '0';
                    
                    setTimeout(() => {
                        lightLogo.style.display = 'none';
                        darkLogo.style.display = 'block';
                    }, 300);
                } else {
                    // Switch to dark theme logo
                    lightLogo.style.opacity = '1';
                    darkLogo.style.opacity = '0';
                    
                    setTimeout(() => {
                        darkLogo.style.display = 'none';
                        lightLogo.style.display = 'block';
                    }, 300);
                }
            }
            
            // Update theme-specific UI elements
            updateThemeSpecificUI(newTheme);
        });
        
        // Function to update theme-specific UI elements
        function updateThemeSpecificUI(theme) {
            // Repaint gradient elements for proper theme colors
            const gradientElements = document.querySelectorAll('.gradient-bg, .hero-background, .premium-section');
            gradientElements.forEach(el => {
                if (el) {
                    el.style.display = 'none';
                    el.offsetHeight; // Force reflow
                    el.style.display = '';
                }
            });
            
            // Toggle any theme-specific classes
            document.querySelectorAll('[data-theme-class]').forEach(el => {
                const classes = el.getAttribute('data-theme-class').split(',');
                if (classes.length === 2) {
                    const [lightClass, darkClass] = classes;
                    el.classList.remove(lightClass, darkClass);
                    el.classList.add(theme === 'dark' ? darkClass : lightClass);
                }
            });
        }
    }

    // Navbar scroll behavior
    const navbar = document.querySelector('.navbar');
    const header = document.querySelector('header');
    let headerHeight = 0;
    
    // Function to update header height
    function updateHeaderHeight() {
        if (header) {
            headerHeight = header.offsetHeight;
        }
    }
    
    // Initialize header height
    updateHeaderHeight();
    
    // Update on resize
    window.addEventListener('resize', updateHeaderHeight);
    
    // Handle navbar visibility on scroll
    if (navbar) {
        window.addEventListener('scroll', () => {
            if (window.scrollY > headerHeight * 0.7) {
                navbar.classList.add('visible');
            } else {
                navbar.classList.remove('visible');
            }
        });
    }

    // Add fade-in animation to header and container
    const headerTitle = document.querySelector('header h1');
    const container = document.querySelector('.container');

    // Trigger animations with slight delay
    if (headerTitle) setTimeout(() => headerTitle.classList.add('fade-in'), 300);
    if (container) setTimeout(() => container.classList.add('fade-in'), 600);
    
    // Add fade-in for service cards
    const serviceCardsFadeIn = document.querySelectorAll('.service-card');
    serviceCardsFadeIn.forEach((card, index) => {
        setTimeout(() => card.classList.add('fade-in'), 800 + (index * 200));
    });

    // Add animations for features
    const featureItems = document.querySelectorAll('.feature-item');
    featureItems.forEach((item, index) => {
        setTimeout(() => item.classList.add('fade-in'), 1000 + (index * 200));
    });

    // Intersection Observer for scroll animations
    if ('IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('fade-in');
                }
            });
        }, { threshold: 0.1 });

        document.querySelectorAll('.container, .service-card, .feature-item, .footer-content').forEach(el => {
            observer.observe(el);
        });

        // Enhanced Intersection Observer for scroll animations
        const observerOptions = {
            threshold: 0.15,
            rootMargin: '0px 0px -100px 0px'
        };

        const sectionObserver = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('visible');
                    
                    // Handle stagger animations for child elements
                    const staggerItems = entry.target.querySelectorAll('.stagger-item');
                    staggerItems.forEach((item, index) => {
                        setTimeout(() => {
                            item.style.opacity = '1';
                            item.style.transform = 'translateY(0)';
                        }, index * 100);
                    });
                }
            });
        }, observerOptions);

        // Observe all sections and items
        document.querySelectorAll('.section-animate, .timeline-item').forEach(el => {
            sectionObserver.observe(el);
        });
    }

    // Add stagger animation classes to items
    document.querySelectorAll('.feature-item, .premium-benefit-card, .category-card, .service-card-listing').forEach((item, index) => {
        item.style.setProperty('--item-index', index);
        item.classList.add('stagger-item');
        item.style.opacity = '0';
        item.style.transform = 'translateY(30px)';
        item.style.transition = 'opacity 0.6s ease, transform 0.6s cubic-bezier(0.175, 0.885, 0.32, 1.275)';
        item.style.transitionDelay = `${index * 0.1}s`;
    });

    // Smooth scroll for anchor links with improved behavior
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            e.preventDefault();
            const targetId = this.getAttribute('href').substring(1);
            const targetElement = document.getElementById(targetId);
            
            if (targetElement) {
                // Calculate position with navbar offset if visible
                const navbarHeight = navbar && navbar.classList.contains('visible') ? navbar.offsetHeight : 0;
                const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - navbarHeight;
                
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
                
                // Update URL without scrolling
                history.pushState(null, null, `#${targetId}`);
            }
        });
    });

    // Enhanced parallax effect for header bubbles
    window.addEventListener('scroll', () => {
        const scrolled = window.pageYOffset;
        const bubbles = document.querySelectorAll('.bubble');
        
        bubbles.forEach((bubble, index) => {
            const speed = 0.05 + (index * 0.02);
            const yPos = scrolled * speed;
            bubble.style.transform = `translate3d(0, ${yPos}px, 0) rotate(${yPos * 0.02}deg)`;
        });
    });
    
    // Enhanced mouse movement effect for hero section
    const heroContent = document.querySelector('.hero-content');
    
    if (heroContent) {
        document.addEventListener('mousemove', (e) => {
            const xPos = (e.clientX / window.innerWidth - 0.5) * 20;
            const yPos = (e.clientY / window.innerHeight - 0.5) * 20;
            
            heroContent.style.transform = `translate3d(${xPos}px, ${yPos}px, 0)`;
        });
    }

    // Set current year in footer
    const yearElement = document.getElementById('year');
    if (yearElement) {
        yearElement.textContent = new Date().getFullYear();
    }

    // Auth Modal Functionality
    const authModal = document.getElementById('authModal');
    if (authModal) {
        const authTabs = document.querySelectorAll('.auth-tab');
        const authForms = document.querySelectorAll('.auth-form');
        const modalTitle = document.getElementById('authModalTitle');

        authModal.addEventListener('show.bs.modal', (event) => {
            const button = event.relatedTarget;
            if (button) {
                const authType = button.getAttribute('data-auth-type');
                if (authType) {
                    switchAuthForm(authType);
                }
            }
        });

        authTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                const authType = tab.getAttribute('data-auth');
                switchAuthForm(authType);
            });
        });

        function switchAuthForm(type) {
            authTabs.forEach(tab => {
                tab.classList.toggle('active', tab.getAttribute('data-auth') === type);
            });

            authForms.forEach(form => {
                form.classList.toggle('active', form.id === `${type}Form`);
            });

            if (modalTitle) {
                modalTitle.textContent = type === 'login' ? 'Login' : 'Sign Up';
            }
        }
    }

    // Handle form submissions
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.addEventListener('submit', (e) => {
            e.preventDefault();
            console.log('Login submitted');
        });
    }

    const signupForm = document.getElementById('signupForm');
    if (signupForm) {
        signupForm.addEventListener('submit', (e) => {
            e.preventDefault();
            console.log('Signup submitted');
        });
    }

    // Monitor theme changes to adjust premium section accordingly
    const premiumSection = document.querySelector('.premium-section');
    if (themeToggle && premiumSection) {
        themeToggle.addEventListener('click', () => {
            // Force repaint the premium section to refresh gradients
            premiumSection.style.display = 'none';
            premiumSection.offsetHeight; // Trigger reflow
            premiumSection.style.display = '';
        });
    }

    // Enhanced smooth scroll for the scroll-down button
    const scrollDownBtn = document.querySelector('.scroll-down-btn');
    if (scrollDownBtn) {
        scrollDownBtn.addEventListener('click', function(e) {
            e.preventDefault();
            
            const targetId = this.getAttribute('href');
            if (!targetId) return;
            
            const targetSection = document.querySelector(targetId);
            
            if (targetSection) {
                // Add a visual indicator that the button was clicked
                this.classList.add('clicked');
                
                // Remove the class after animation completes
                setTimeout(() => {
                    this.classList.remove('clicked');
                }, 700);
                
                // Get the navbar height if it's visible
                const navbarHeight = navbar && navbar.classList.contains('visible') ? navbar.offsetHeight : 0;
                
                // Calculate the scroll position
                const targetPosition = targetSection.getBoundingClientRect().top + window.pageYOffset - navbarHeight;
                
                // Scroll to the target section smoothly
                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            }
        });
    }

    // Improved Theme Handler for dynamic changes
    setupThemeHandler();

    // Initialize category filters when DOM is loaded
    setupCategoryFilters();

    // Optimize service cards loading
    const serviceCards = document.querySelectorAll('.service-card');
    let loadedImages = 0;
    const totalImages = serviceCards.length;

    // Use Intersection Observer for lazy loading
    const loadingObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const card = entry.target;
                const img = card.querySelector('.service-image');
                
                if (img) {
                    // Load image
                    const src = img.getAttribute('src');
                    const newImg = new Image();
                    
                    newImg.onload = () => {
                        img.src = src;
                        card.setAttribute('data-loaded', 'true');
                        loadedImages++;
                        
                        if (loadedImages === totalImages) {
                            document.querySelector('.services-grid').classList.add('all-loaded');
                        }
                    };
                    
                    newImg.src = src;
                }
                
                observer.unobserve(card);
            }
        });
    }, {
        threshold: 0.1,
        rootMargin: '50px'
    });

    // Observe each service card
    serviceCards.forEach(card => {
        card.setAttribute('data-loaded', 'false');
        loadingObserver.observe(card);
    });

    // Optimize animation performance
    const debouncedFilter = debounce(filterAndSortServices, 150);
    
    // Replace existing event listeners with debounced version
    searchInput?.addEventListener('input', debouncedFilter);
    minPriceInput?.addEventListener('input', debouncedFilter);
    maxPriceInput?.addEventListener('input', debouncedFilter);

    // Debounce function for better performance
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Use requestAnimationFrame for smooth animations
    function animateCards(cards) {
        cards.forEach((card, index) => {
            requestAnimationFrame(() => {
                setTimeout(() => {
                    card.style.opacity = '1';
                    card.style.transform = 'translateY(0)';
                }, index * 30);
            });
        });
    }

    // Add scroll position restoration
    const scrollPos = sessionStorage.getItem('servicesScrollPos');
    if (scrollPos) {
        window.scrollTo(0, parseInt(scrollPos));
        sessionStorage.removeItem('servicesScrollPos');
    }

    // Save scroll position when leaving page
    window.addEventListener('beforeunload', () => {
        sessionStorage.setItem('servicesScrollPos', window.scrollY.toString());
    });
});

// Fix image placeholder errors by adding this to your CSS
document.head.insertAdjacentHTML('beforeend', `
    <style>
        /* Replace placeholder with fallback solution */
        img[src="https://via.placeholder.com/500x400"] {
            background-color: #f0f0f0;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 500px;
            max-width: 100%;
            height: 400px;
            position: relative;
        }
        
        img[src="https://via.placeholder.com/500x400"]::after {
            content: "Image";
            position: absolute;
            font-family: Arial, sans-serif;
            color: #888;
            font-size: 1.5rem;
        }
        
        .scroll-down-btn.clicked {
            animation: clickPulse 0.7s ease-out;
            background: rgba(var(--primary-rgb), 0.4) !important;
        }
        
        @keyframes clickPulse {
            0% { transform: scale(1); }
            50% { transform: scale(0.95); }
            100% { transform: scale(1); }
        }
        
        /* Add extra attention to the scroll button with this overlay */
        .scroll-down-btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, rgba(255,255,255,0) 0%, rgba(255,255,255,0.3) 50%, rgba(255,255,255,0) 100%);
            transform: translateX(-100%);
            transition: all 0.5s ease;
        }
        
        .scroll-down-btn:hover::before {
            transform: translateX(100%);
        }

        /* Theme transition styles */
        body, .navbar, .footer, .card, .btn, input, textarea, select, .hero-section, .footer-content {
            transition: 
                background-color 0.4s ease,
                color 0.3s ease, 
                border-color 0.3s ease, 
                box-shadow 0.3s ease;
        }
        
        /* Logo transition improvements */
        .logo-light, .logo-dark {
            transition: opacity 0.3s ease;
            position: absolute;
            top: 0;
            left: 0;
        }
        
        .navbar-brand {
            position: relative;
            height: 40px;
            width: 120px;
            display: block;
        }
        
        /* Theme toggle button animation */
        #themeToggle {
            position: relative;
            overflow: hidden;
        }
        
        #themeToggle:after {
            content: '';
            position: absolute;
            top: 50%;
            left: 50%;
            width: 5px;
            height: 5px;
            background: rgba(var(--primary-rgb), 0.3);
            opacity: 0;
            border-radius: 100%;
            transform: scale(1) translate(-50%, -50%);
            transform-origin: 50% 50%;
        }
        
        #themeToggle:active:after {
            opacity: 1;
            transform: scale(15) translate(-50%, -50%);
            transition: transform 0.3s ease, opacity 0.2s ease;
        }
        
        /* Smooth theme transitions */
        *, *::before, *::after {
            transition: 
                background-color 0.3s ease,
                color 0.3s ease, 
                border-color 0.3s ease, 
                box-shadow 0.3s ease,
                filter 0.3s ease,
                opacity 0.3s ease;
        }
        
        /* Prevent transitions on page load */
        .preload * {
            transition: none !important;
        }
    </style>
`);

// Setup comprehensive theme handler
function setupThemeHandler() {
    // Process theme changes when they occur
    document.addEventListener('themeChanged', handleThemeChange);
    
    // Add a click handler to the theme toggle to force refresh
    const themeToggle = document.getElementById('themeToggle');
    if (themeToggle) {
        // Ensure we handle theme toggle directly in script.js too
        themeToggle.addEventListener('click', () => {
            const currentTheme = document.documentElement.getAttribute('data-bs-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            
            // Delay to ensure the theme changes are processed
            setTimeout(() => {
                handleThemeChange({ detail: { theme: newTheme } });
            }, 50);
        }, { once: false });
    }
    
    // Initial processing on page load
    const currentTheme = document.documentElement.getAttribute('data-bs-theme');
    if (currentTheme) {
        setTimeout(() => {
            handleThemeChange({ detail: { theme: currentTheme } });
        }, 100);
    }
}

// Handle theme changes throughout the application
function handleThemeChange(event) {
    const theme = event.detail.theme;
    
    // 1. Handle logo transitions
    updateLogos(theme);
    
    // 2. Update any custom theme-dependent elements
    updateCustomElements(theme);
    
    // 3. Force a final refresh
    forceFullRefresh();
}

// Update logo display based on theme
function updateLogos(theme) {
    const lightLogo = document.querySelector('.logo-light');
    const darkLogo = document.querySelector('.logo-dark');
    
    if (lightLogo && darkLogo) {
        if (theme === 'light') {
            // Transition to light theme
            lightLogo.style.opacity = '0';
            darkLogo.style.opacity = '1';
            
            setTimeout(() => {
                lightLogo.style.display = 'none';
                darkLogo.style.display = 'block';
            }, 150);
        } else {
            // Transition to dark theme
            darkLogo.style.opacity = '0';
            lightLogo.style.opacity = '1';
            
            setTimeout(() => {
                darkLogo.style.display = 'none';
                lightLogo.style.display = 'block';
            }, 150);
        }
    }
}

// Force a full refresh of theme elements
function forceFullRefresh() {
    // Create a style element to force a complete reflow
    const style = document.createElement('style');
    document.head.appendChild(style);
    document.head.removeChild(style);
    
    // Force reflow of entire document
    document.body.style.zoom = 1.0001;
    setTimeout(() => document.body.style.zoom = 1, 10);
    
    // Force reflow of theme-sensitive elements
    document.querySelectorAll('.navbar, .card, .hero-section, .premium-section, .footer, [class*="bg-"], .gradient-bg').forEach(el => {
        if (el) {
            el.style.display = 'none';
            el.offsetHeight; // Trigger reflow
            el.style.display = '';
        }
    });
}

// Update custom elements that have theme-specific behavior
function updateCustomElements(theme) {
    // Toggle any theme-specific classes
    document.querySelectorAll('[data-theme-class]').forEach(el => {
        const classes = el.getAttribute('data-theme-class').split(',');
        if (classes.length === 2) {
            const [lightClass, darkClass] = classes;
            el.classList.remove(lightClass, darkClass);
            el.classList.add(theme === 'dark' ? darkClass : lightClass);
        }
    });
    
    // Update any content that needs changing based on theme
    document.querySelectorAll('[data-theme-content]').forEach(el => {
        const contents = el.getAttribute('data-theme-content').split(',');
        if (contents.length === 2) {
            const [lightContent, darkContent] = contents;
            el.textContent = theme === 'dark' ? darkContent : lightContent;
        }
    });
}

// Remove preload class after page load to enable transitions
window.addEventListener('load', function() {
    document.body.classList.remove('preload');
});

// Add preload class to prevent transitions on initial load
document.body.classList.add('preload');

// Check if user is logged in and update UI accordingly
function checkUserLoginStatus() {
    try {
        const user = JSON.parse(localStorage.getItem('currentUser'));
        const loggedOutNav = document.querySelector('.logged-out-nav');
        const userProfileNav = document.querySelector('.user-profile-nav');
        
        if (user) {
            // User is logged in
            if (loggedOutNav) loggedOutNav.classList.add('d-none');
            if (userProfileNav) {
                userProfileNav.classList.remove('d-none');
                
                // Update user name
                const userName = userProfileNav.querySelector('.user-name');
                if (userName) {
                    userName.textContent = user.name || user.email.split('@')[0];
                }
            }
            
            // Check if user is admin
            const adminEmail = "support@xteam.tn";
            const isAdmin = user.email && typeof user.email === 'string' && 
                        user.email.toLowerCase().trim() === adminEmail.toLowerCase();
            
            if (isAdmin) {
                // Add admin-specific UI elements
                const userNavItem = document.querySelector('.user-nav-item');
                if (userNavItem) {
                    userNavItem.classList.remove('d-none');
                    const userNavLink = userNavItem.querySelector('a');
                    if (userNavLink) {
                        userNavLink.innerHTML = '<i class="bi bi-speedometer2"></i> Admin Dashboard';
                    }
                }
            } else {
                // Regular user UI elements
                const userNavItem = document.querySelector('.user-nav-item');
                if (userNavItem) {
                    userNavItem.classList.remove('d-none');
                }
            }
        }
    } catch (e) {
        console.error('Error checking user login status:', e);
    }
}

// Create a special helper function for theme toggling
window.forceReapplyTheme = function() {
    const currentTheme = document.documentElement.getAttribute('data-bs-theme');
    const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
    
    // Save theme preference
    localStorage.setItem('theme', newTheme);
    
    // Apply theme change
    document.documentElement.setAttribute('data-bs-theme', newTheme);
    
    // Dispatch event for theme change
    document.dispatchEvent(new CustomEvent('themeChanged', { 
        detail: { theme: newTheme }
    }));
    
    // Update the theme toggle icon
    const themeToggleIcon = document.querySelector('#themeToggle i');
    if (themeToggleIcon) {
        themeToggleIcon.classList.remove('bi-sun-fill', 'bi-moon-fill');
        themeToggleIcon.classList.add(newTheme === 'dark' ? 'bi-moon-fill' : 'bi-sun-fill');
    }
    
    // Force refresh
    forceFullRefresh();
};

// Category filtering
function setupCategoryFilters() {
    const categoryInputs = document.querySelectorAll('input[name="category"]');
    const serviceCards = document.querySelectorAll('.service-card');
    
    categoryInputs.forEach(input => {
        input.addEventListener('change', (e) => {
            const selectedCategory = e.target.value;
            
            serviceCards.forEach(card => {
                const cardCategory = card.getAttribute('data-category');
                if (selectedCategory === 'all' || cardCategory === selectedCategory) {
                    card.style.display = '';
                    setTimeout(() => {
                        card.style.opacity = '1';
                        card.style.transform = 'translateY(0)';
                    }, 50);
                } else {
                    card.style.opacity = '0';
                    card.style.transform = 'translateY(20px)';
                    setTimeout(() => {
                        card.style.display = 'none';
                    }, 300);
                }
            });
        });
    });
}