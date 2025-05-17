/**
 * Main JavaScript functionality for the website
 * Enhanced with modern animation and interaction effects
 */

document.addEventListener('DOMContentLoaded', function() {
    // Initialize the scroll reveal animations
    initScrollReveal();
    
    // Initialize smooth scrolling for anchor links
    initSmoothScroll();
    
    // Initialize parallax effects
    initParallax();
    
    // Initialize other interactive elements
    initInteractiveElements();
});

/**
 * Initialize scroll reveal animations using Intersection Observer API
 * This handles revealing elements as they enter the viewport
 */
function initScrollReveal() {
    // Select all elements with reveal classes
    const elements = document.querySelectorAll('.reveal, .reveal-left, .reveal-right, .reveal-scale');
    
    if ('IntersectionObserver' in window && elements.length > 0) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    entry.target.classList.add('active');
                    // Only animate once
                    observer.unobserve(entry.target);
                }
            });
        }, {
            root: null,
            rootMargin: '0px',
            threshold: 0.15
        });
        
        elements.forEach(element => {
            observer.observe(element);
        });
    } else {
        // Fallback for browsers that don't support Intersection Observer
        elements.forEach(element => {
            element.classList.add('active');
        });
    }
    
    // Add staggered animation delay to groups of elements
    const animationGroups = document.querySelectorAll('.animation-group');
    animationGroups.forEach(group => {
        const children = group.querySelectorAll('.animation-element');
        children.forEach((element, index) => {
            element.style.animationDelay = `${0.1 + (index * 0.1)}s`;
        });
    });
}

/**
 * Initialize smooth scrolling for all anchor links
 */
function initSmoothScroll() {
    // Select all links with hashes
    document.querySelectorAll('a[href*="#"]:not([href="#"])').forEach(anchor => {
        anchor.addEventListener('click', function(e) {
            // Check if the link is on the same page
            if (
                location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') &&
                location.hostname === this.hostname
            ) {
                // Find the target element
                const target = document.querySelector(this.hash);
                if (target) {
                    e.preventDefault();
                    
                    // Get header height for offset
                    const headerHeight = document.querySelector('header') ? 
                        document.querySelector('header').offsetHeight : 0;
                        
                    // Get navbar height if fixed
                    const navbarHeight = document.querySelector('.navbar') ? 
                        document.querySelector('.navbar').offsetHeight : 0;
                        
                    // Calculate total offset
                    const offset = headerHeight + navbarHeight + 20;
                    
                    // Calculate target position
                    const targetPosition = target.getBoundingClientRect().top + window.scrollY - offset;
                    
                    // Scroll smoothly to the target
                    window.scrollTo({
                        top: targetPosition,
                        behavior: 'smooth'
                    });
                    
                    // Update URL hash after scrolling
                    setTimeout(() => {
                        history.pushState(null, null, this.hash);
                    }, 1000);
                }
            }
        });
    });
}

/**
 * Initialize parallax effects for elements with the parallax class
 */
function initParallax() {
    const parallaxElements = document.querySelectorAll('.parallax');
    
    if (parallaxElements.length > 0) {
        window.addEventListener('scroll', () => {
            const scrollTop = window.scrollY;
            
            parallaxElements.forEach(element => {
                // Get the element's position and speed
                const elementTop = element.offsetTop;
                const elementHeight = element.offsetHeight;
                const viewportHeight = window.innerHeight;
                const scrollPosition = scrollTop + viewportHeight;
                
                // Check if element is in viewport
                if (scrollPosition > elementTop && scrollTop < elementTop + elementHeight) {
                    const speed = element.dataset.speed || 0.15;
                    const yPos = (scrollPosition - elementTop) * speed;
                    
                    // Apply transform
                    element.style.transform = `translateY(${yPos}px)`;
                }
            });
        });
    }
    
    // Alternative parallax for background images
    const parallaxBackgrounds = document.querySelectorAll('.parallax-bg');
    
    if (parallaxBackgrounds.length > 0) {
        window.addEventListener('scroll', () => {
            const scrollTop = window.scrollY;
            
            parallaxBackgrounds.forEach(element => {
                // Calculate parallax offset
                const speed = element.dataset.speed || 0.5;
                const yPos = scrollTop * speed;
                
                // Apply background position
                element.style.backgroundPosition = `center ${-yPos}px`;
            });
        });
    }
}

/**
 * Initialize interactive elements and UI enhancements
 */
function initInteractiveElements() {
    // Enhance card hover effects
    const cards = document.querySelectorAll('.card');
    cards.forEach(card => {
        card.addEventListener('mouseenter', function() {
            this.classList.add('card-hover');
        });
        
        card.addEventListener('mouseleave', function() {
            this.classList.remove('card-hover');
        });
    });
    
    // Enhanced form validation with interactive feedback
    const forms = document.querySelectorAll('form.validate-form');
    forms.forEach(form => {
        const inputs = form.querySelectorAll('input, textarea, select');
        
        inputs.forEach(input => {
            // Show validation feedback on blur
            input.addEventListener('blur', function() {
                if (this.checkValidity()) {
                    this.classList.remove('is-invalid');
                    this.classList.add('is-valid');
                } else if (this.value !== '') {
                    this.classList.remove('is-valid');
                    this.classList.add('is-invalid');
                }
            });
            
            // Remove validation styles on focus
            input.addEventListener('focus', function() {
                this.classList.remove('is-invalid', 'is-valid');
            });
        });
        
        // Prevent submission if invalid and show feedback
        form.addEventListener('submit', function(e) {
            if (!this.checkValidity()) {
                e.preventDefault();
                e.stopPropagation();
                
                // Highlight all invalid fields
                const invalidInputs = this.querySelectorAll(':invalid');
                invalidInputs.forEach(input => {
                    input.classList.add('is-invalid');
                });
                
                // Scroll to first invalid input
                if (invalidInputs.length > 0) {
                    invalidInputs[0].scrollIntoView({ behavior: 'smooth', block: 'center' });
                    invalidInputs[0].focus();
                }
            }
            
            this.classList.add('was-validated');
        });
    });
    
    // Initialize tooltips (requires Bootstrap JS)
    if (typeof bootstrap !== 'undefined' && bootstrap.Tooltip) {
        const tooltips = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        tooltips.forEach(tooltip => {
            new bootstrap.Tooltip(tooltip);
        });
    }
    
    // Initialize popovers (requires Bootstrap JS)
    if (typeof bootstrap !== 'undefined' && bootstrap.Popover) {
        const popovers = document.querySelectorAll('[data-bs-toggle="popover"]');
        popovers.forEach(popover => {
            new bootstrap.Popover(popover);
        });
    }
    
    // Add animated counters 
    initCounters();
    
    // Add interactive cursor effects
    initCursorEffects();
    
    // Add floating elements animation
    initFloatingElements();
}

/**
 * Initialize animated counters
 */
function initCounters() {
    const counters = document.querySelectorAll('.counter');
    
    if (counters.length > 0 && 'IntersectionObserver' in window) {
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const counter = entry.target;
                    const target = parseInt(counter.getAttribute('data-target'));
                    const duration = parseInt(counter.getAttribute('data-duration')) || 2000;
                    const countTo = target;
                    
                    let count = 0;
                    const startTime = performance.now();
                    
                    function updateCount(currentTime) {
                        const elapsed = currentTime - startTime;
                        const progress = Math.min(elapsed / duration, 1);
                        
                        // Using easeOutQuart easing function for natural counting
                        const easeProgress = 1 - Math.pow(1 - progress, 4);
                        count = Math.floor(easeProgress * countTo);
                        
                        counter.innerText = count.toLocaleString();
                        
                        if (progress < 1) {
                            requestAnimationFrame(updateCount);
                        } else {
                            counter.innerText = target.toLocaleString();
                            observer.unobserve(counter);
                        }
                    }
                    
                    requestAnimationFrame(updateCount);
                }
            });
        }, {
            threshold: 0.2
        });
        
        counters.forEach(counter => {
            observer.observe(counter);
        });
    }
}

/**
 * Add subtle cursor effects for interactive elements
 */
function initCursorEffects() {
    // Create cursor element if enabled by data attribute
    if (document.body.getAttribute('data-cursor-effects') === 'true') {
        const cursor = document.createElement('div');
        cursor.classList.add('custom-cursor');
        document.body.appendChild(cursor);
        
        const cursorDot = document.createElement('div');
        cursorDot.classList.add('cursor-dot');
        document.body.appendChild(cursorDot);
        
        // Update cursor position
        document.addEventListener('mousemove', e => {
            cursor.style.transform = `translate(${e.clientX}px, ${e.clientY}px)`;
            cursorDot.style.transform = `translate(${e.clientX}px, ${e.clientY}px)`;
        });
        
        // Add hover effects for interactive elements
        const interactiveElements = document.querySelectorAll('a, button, .card, .btn, input, textarea, select');
        
        interactiveElements.forEach(el => {
            el.addEventListener('mouseenter', () => {
                cursor.classList.add('cursor-hover');
                cursorDot.classList.add('cursor-dot-hover');
            });
            
            el.addEventListener('mouseleave', () => {
                cursor.classList.remove('cursor-hover');
                cursorDot.classList.remove('cursor-dot-hover');
            });
        });
    }
}

/**
 * Initialize floating animations for elements
 */
function initFloatingElements() {
    const floatingElements = document.querySelectorAll('.floating');
    
    floatingElements.forEach((element, index) => {
        // Generate random values for floating animation
        const randomX = Math.random() * 15;
        const randomY = Math.random() * 15;
        const randomDelay = Math.random() * 2;
        const randomDuration = 3 + Math.random() * 2;
        
        // Apply the animation with random values
        element.style.animation = `float ${randomDuration}s ease-in-out ${randomDelay}s infinite alternate`;
        element.style.transform = `translate(0, 0)`;
        
        // Create a keyframe animation unique to this element
        const keyframes = `
        @keyframes float-${index} {
            0% { transform: translate(0, 0); }
            100% { transform: translate(${randomX}px, ${-randomY}px); }
        }`;
        
        // Add the keyframes to the document
        const styleElement = document.createElement('style');
        styleElement.appendChild(document.createTextNode(keyframes));
        document.head.appendChild(styleElement);
        
        // Apply the unique animation
        element.style.animation = `float-${index} ${randomDuration}s ease-in-out ${randomDelay}s infinite alternate`;
    });
}

/**
 * Add blur effect to page content when modal is open
 */
function initModalBlurEffect() {
    const modals = document.querySelectorAll('.modal');
    const pageContent = document.querySelector('.page-content') || document.querySelector('main');
    
    if (modals.length > 0 && pageContent) {
        modals.forEach(modal => {
            modal.addEventListener('show.bs.modal', () => {
                pageContent.classList.add('modal-blur');
            });
            
            modal.addEventListener('hidden.bs.modal', () => {
                pageContent.classList.remove('modal-blur');
            });
        });
    }
}

/**
 * Lazy load images for better performance
 */
function initLazyLoading() {
    if ('loading' in HTMLImageElement.prototype) {
        // Browser supports native lazy loading
        const lazyImages = document.querySelectorAll('img[loading="lazy"]');
        lazyImages.forEach(img => {
            img.src = img.dataset.src;
            if (img.dataset.srcset) {
                img.srcset = img.dataset.srcset;
            }
        });
    } else {
        // Fallback for browsers that don't support native lazy loading
        const lazyImages = document.querySelectorAll('.lazy-image');
        
        if (lazyImages.length > 0 && 'IntersectionObserver' in window) {
            const imageObserver = new IntersectionObserver((entries, observer) => {
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                        const image = entry.target;
                        image.src = image.dataset.src;
                        if (image.dataset.srcset) {
                            image.srcset = image.dataset.srcset;
                        }
                        image.classList.remove('lazy-image');
                        image.classList.add('lazy-loaded');
                        observer.unobserve(image);
                    }
                });
            });
            
            lazyImages.forEach(image => {
                imageObserver.observe(image);
            });
        }
    }
}

/**
 * Handle sticky elements like headers, sidebar, etc.
 */
function initStickyElements() {
    const stickyElements = document.querySelectorAll('.sticky-element');
    
    if (stickyElements.length > 0) {
        window.addEventListener('scroll', () => {
            const scrollTop = window.scrollY;
            
            stickyElements.forEach(element => {
                const stickyOffset = element.dataset.stickyOffset || 0;
                
                if (scrollTop > stickyOffset) {
                    element.classList.add('is-sticky');
                } else {
                    element.classList.remove('is-sticky');
                }
            });
        });
    }
}

/**
 * Create a typed text animation effect
 * Requires an element with .typed-text class and data-typed-strings attribute
 */
function initTypedTextEffect() {
    const typedElements = document.querySelectorAll('.typed-text');
    
    typedElements.forEach(element => {
        const stringsAttr = element.getAttribute('data-typed-strings');
        if (!stringsAttr) return;
        
        const strings = stringsAttr.split(',');
        let currentStringIndex = 0;
        let currentCharIndex = 0;
        let isDeleting = false;
        let typingSpeed = 100;
        
        function type() {
            const currentString = strings[currentStringIndex];
            
            if (isDeleting) {
                // Removing characters
                element.textContent = currentString.substring(0, currentCharIndex - 1);
                currentCharIndex--;
                typingSpeed = 50; // Faster when deleting
            } else {
                // Adding characters
                element.textContent = currentString.substring(0, currentCharIndex + 1);
                currentCharIndex++;
                typingSpeed = 100 + Math.random() * 50; // Slightly variable when typing
            }
            
            // Check if word is complete
            if (!isDeleting && currentCharIndex === currentString.length) {
                // Pause at the end of typing
                typingSpeed = 1500;
                isDeleting = true;
            } else if (isDeleting && currentCharIndex === 0) {
                // Move to next string once deleted
                isDeleting = false;
                currentStringIndex = (currentStringIndex + 1) % strings.length;
                typingSpeed = 500; // Pause before starting new word
            }
            
            setTimeout(type, typingSpeed);
        }
        
        // Start the typing animation
        setTimeout(type, 1000);
    });
}

// Initialize everything when document is fully loaded
window.addEventListener('load', function() {
    // Initialize lazy loading
    initLazyLoading();
    
    // Initialize sticky elements
    initStickyElements();
    
    // Initialize modal blur effects
    initModalBlurEffect();
    
    // Initialize typed text effects
    initTypedTextEffect();
    
    // Add additional animation classes based on viewport size
    const isMobile = window.innerWidth < 768;
    
    if (!isMobile) {
        document.querySelectorAll('.animate-desktop').forEach(el => {
            el.classList.add('animate');
        });
    } else {
        document.querySelectorAll('.animate-mobile').forEach(el => {
            el.classList.add('animate');
        });
    }
    
    // Remove page loader if present
    const pageLoader = document.querySelector('.page-loader');
    if (pageLoader) {
        pageLoader.classList.add('loaded');
        setTimeout(() => {
            pageLoader.style.display = 'none';
        }, 500);
    }
});