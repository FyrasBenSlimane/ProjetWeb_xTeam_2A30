/**
 * Community Dashboard Enhancements
 * Provides interactive features and UI improvements for all community pages
 */

document.addEventListener('DOMContentLoaded', function() {
    // Apply community styling to all relevant elements
    applyCommunityStyling();
    
    // Initialize form enhancements if on a form page
    initializeFormEnhancements();
    
    // Initialize search functionality
    initializeSearch();
    
    // Add animation effects to cards
    animateElements();
});

/**
 * Apply community styling to elements
 */
function applyCommunityStyling() {
    // Enhance cards with community styling
    document.querySelectorAll('.card').forEach(card => {
        card.classList.add('community-card');
    });
    
    // Enhance breadcrumbs
    const breadcrumb = document.querySelector('.breadcrumb');
    if (breadcrumb) {
        breadcrumb.closest('nav').classList.add('community-breadcrumb');
    }
    
    // Enhance container headers
    const containerHeaders = document.querySelectorAll('.container > .row:first-child');
    containerHeaders.forEach(header => {
        if (header.querySelector('h1')) {
            header.classList.add('community-header');
            header.classList.remove('mb-4'); // Remove default margin to use the one in community-header
        }
    });
    
    // Enhance buttons
    document.querySelectorAll('.btn-primary').forEach(btn => {
        btn.classList.add('btn-community-primary');
    });
    
    document.querySelectorAll('.btn-outline-primary').forEach(btn => {
        btn.classList.add('btn-community-outline');
    });
}

/**
 * Initialize form enhancements
 */
function initializeFormEnhancements() {
    // Check if we're on a form page
    const forms = document.querySelectorAll('form');
    if (forms.length === 0) return;
    
    // Enhance character counters
    const titleCharCount = document.getElementById('title-char-count');
    const contentCharCount = document.getElementById('content-char-count');
    
    if (titleCharCount) {
        titleCharCount.classList.add('char-count');
    }
    
    if (contentCharCount) {
        contentCharCount.classList.add('char-count');
    }
    
    // Add floating labels effect
    document.querySelectorAll('.form-control, .form-select').forEach(input => {
        input.addEventListener('focus', function() {
            this.parentElement.classList.add('focused');
        });
        
        input.addEventListener('blur', function() {
            this.parentElement.classList.remove('focused');
            if (this.value.trim() !== '') {
                this.classList.add('has-value');
            } else {
                this.classList.remove('has-value');
            }
        });
        
        // Initialize state for inputs with values
        if (input.value.trim() !== '') {
            input.classList.add('has-value');
        }
    });
    
    // Add form submission feedback
    forms.forEach(form => {
        form.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Submitting...';
                submitBtn.disabled = true;
            }
        });
    });
}

/**
 * Initialize search functionality
 */
function initializeSearch() {
    const searchInput = document.querySelector('.forum-search-input');
    const searchResultsDropdown = document.querySelector('.search-results-dropdown');
    
    if (!searchInput || !searchResultsDropdown) return;
    
    searchInput.addEventListener('focus', function() {
        this.parentElement.classList.add('focused');
    });
    
    searchInput.addEventListener('blur', function() {
        setTimeout(() => {
            this.parentElement.classList.remove('focused');
            searchResultsDropdown.classList.add('d-none');
        }, 200);
    });
    
    searchInput.addEventListener('input', function() {
        const query = this.value.trim();
        
        if (query.length < 3) {
            searchResultsDropdown.classList.add('d-none');
            return;
        }
        
        // Show loading state
        searchResultsDropdown.classList.remove('d-none');
        searchResultsDropdown.innerHTML = `
            <div class="p-3 text-center">
                <div class="spinner-border spinner-border-sm text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mb-0 mt-2 small text-muted">Searching...</p>
            </div>
        `;
        
        // In a real implementation, you would make an AJAX call here
        // For demo purposes, we'll just simulate a search result after a delay
        setTimeout(() => {
            if (query.length >= 3) {
                searchResultsDropdown.innerHTML = `
                    <div class="p-2">
                        <h6 class="dropdown-header">Search Results</h6>
                        <a href="#" class="dropdown-item py-2">
                            <div class="d-flex align-items-center">
                                <div class="me-2">
                                    <i class="bi bi-chat-text text-primary"></i>
                                </div>
                                <div>
                                    <div class="fw-medium">Result matching "${query}"</div>
                                    <small class="text-muted">Found in topics</small>
                                </div>
                            </div>
                        </a>
                        <a href="#" class="dropdown-item py-2">
                            <div class="d-flex align-items-center">
                                <div class="me-2">
                                    <i class="bi bi-people text-primary"></i>
                                </div>
                                <div>
                                    <div class="fw-medium">Another result for "${query}"</div>
                                    <small class="text-muted">Found in groups</small>
                                </div>
                            </div>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="#" class="dropdown-item text-center small text-primary">View all results</a>
                    </div>
                `;
            }
        }, 500);
    });
}

/**
 * Add animation effects to elements
 */
function animateElements() {
    // Add fade-in animation to cards
    const cards = document.querySelectorAll('.card');
    cards.forEach((card, index) => {
        card.classList.add('animate-fade-in');
        card.style.animationDelay = `${index * 0.1}s`;
    });
    
    // Add hover effects to list items
    const listItems = document.querySelectorAll('.list-group-item');
    listItems.forEach(item => {
        item.addEventListener('mouseenter', function() {
            this.style.backgroundColor = 'rgba(13, 110, 253, 0.05)';
            this.style.transition = 'background-color 0.2s';
        });
        
        item.addEventListener('mouseleave', function() {
            this.style.backgroundColor = '';
        });
    });
}