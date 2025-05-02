/**
 * Forum.js - JavaScript functionality for the community forum
 * Handles interactive features, AJAX requests, and UI enhancements
 */

document.addEventListener('DOMContentLoaded', function () {
    // Initialize all forum features
    Forum.init();
});

/**
 * Main Forum object containing all functionality
 */
const Forum = {
    // Configuration settings
    config: {
        ajaxUrl: window.location.origin + '/community/',
        refreshInterval: 30000, // 30 seconds
        animationSpeed: 300,
        debounceTime: 500,
        csrfToken: document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
    },

    // Cache DOM elements
    elements: {},

    /**
     * Initialize the forum functionality
     */
    init: function () {
        // Cache frequently used elements
        this.cacheElements();

        // Initialize features
        this.initTooltips();
        this.setupSearchAutocomplete();
        this.initTopicVoting();
        this.setupQuickReply();
        this.initDraftSaving();
        this.setupTopicFilter();
        this.setupImagePreview();
        this.setupInfiniteScroll();
        this.initMarkdownEditor();
        this.setupNotifications();
        this.setupMobileNav();

        // Initialize topic page functionality if we're on a topic page
        const topicId = this.getTopicIdFromUrl();
        if (topicId) {
            this.initializeTopicPage(topicId);
        }
    },

    /**
     * Get topic ID from URL
     * @returns {string|null} Topic ID or null if not found
     */
    getTopicIdFromUrl: function () {
        // Try to get ID from query string
        const urlParams = new URLSearchParams(window.location.search);
        const idParam = urlParams.get('id');

        if (idParam) return idParam;

        // Try to get ID from path segment
        const pathSegments = window.location.pathname.split('/');
        const topicIndex = pathSegments.indexOf('topic');

        if (topicIndex !== -1 && pathSegments.length > topicIndex + 1) {
            return pathSegments[topicIndex + 1];
        }

        return null;
    },

    /**
     * Cache frequently used DOM elements
     */
    cacheElements: function () {
        this.elements = {
            searchForm: document.querySelector('.forum-search-form'),
            searchInput: document.querySelector('.forum-search-input'),
            searchResults: document.querySelector('.search-results-dropdown'),
            topicsList: document.querySelector('.topics-list'),
            replyForm: document.querySelector('.quick-reply-form'),
            replyContent: document.querySelector('.quick-reply-content'),
            topicContent: document.querySelector('.topic-content'),
            voteButtons: document.querySelectorAll('.topic-vote-btn'),
            replyButtons: document.querySelectorAll('.reply-btn'),
            filterDropdown: document.querySelector('.filter-dropdown'),
            notificationBell: document.querySelector('.notification-bell'),
            mobileNavToggle: document.querySelector('.mobile-nav-toggle')
        };
    },

    /**
     * Initialize Bootstrap tooltips
     */
    initTooltips: function () {
        const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
        if (tooltipTriggerList.length) {
            [...tooltipTriggerList].map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        }
    },

    /**
     * Setup search autocomplete functionality
     */
    setupSearchAutocomplete: function () {
        const searchInput = this.elements.searchInput;
        const searchResults = this.elements.searchResults;

        if (!searchInput) return;

        // Setup debounced search
        let debounceTimeout;

        searchInput.addEventListener('input', (e) => {
            const query = e.target.value.trim();

            clearTimeout(debounceTimeout);

            if (query.length < 3) {
                if (searchResults) {
                    searchResults.innerHTML = '';
                    searchResults.classList.add('d-none');
                }
                return;
            }

            debounceTimeout = setTimeout(() => {
                this.performSearch(query);
            }, this.config.debounceTime);
        });

        // Close search results when clicking outside
        document.addEventListener('click', (e) => {
            if (searchResults && !searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.classList.add('d-none');
            }
        });
    },

    /**
     * Perform AJAX search for autocomplete
     * @param {string} query - Search query
     */
    performSearch: function (query) {
        const searchResults = this.elements.searchResults;
        if (!searchResults) return;

        // Show loading indicator
        searchResults.innerHTML = '<div class="p-3 text-center"><div class="spinner-border spinner-border-sm text-primary" role="status"></div></div>';
        searchResults.classList.remove('d-none');

        // Fetch search results via AJAX
        fetch(`${this.config.ajaxUrl}quickSearch?q=${encodeURIComponent(query)}`)
            .then(response => response.json())
            .then(data => {
                if (data.topics && data.topics.length > 0) {
                    let html = '<div class="list-group list-group-flush">';

                    data.topics.slice(0, 5).forEach(topic => {
                        const title = this.highlightSearchTerm(topic.title, query);
                        html += `
                            <a href="${this.config.ajaxUrl}topic/${topic.slug}" class="list-group-item list-group-item-action p-2">
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-light text-dark me-2">${topic.category_name}</span>
                                    <h6 class="mb-0 text-truncate">${title}</h6>
                                </div>
                                <div class="small text-muted mt-1">
                                    <i class="bi bi-chat-text me-1"></i> ${topic.reply_count} replies
                                    <i class="bi bi-eye ms-2 me-1"></i> ${topic.views} views
                                </div>
                            </a>
                        `;
                    });

                    html += `
                        <div class="list-group-item text-center p-2">
                            <a href="${this.config.ajaxUrl}searchTopics?search=${encodeURIComponent(query)}" class="btn btn-sm btn-primary w-100">
                                See all results
                            </a>
                        </div>
                    </div>`;

                    searchResults.innerHTML = html;
                } else {
                    searchResults.innerHTML = `
                        <div class="p-3 text-center">
                            <div class="text-muted">No results found</div>
                            <a href="${this.config.ajaxUrl}searchTopics?search=${encodeURIComponent(query)}" class="btn btn-sm btn-outline-primary mt-2">
                                Try advanced search
                            </a>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Search error:', error);
                searchResults.innerHTML = '<div class="p-3 text-center text-danger">Error loading results</div>';
            });
    },

    /**
     * Highlight search term in text
     * @param {string} text - Text to highlight in
     * @param {string} term - Term to highlight
     * @returns {string} HTML with highlighted term
     */
    highlightSearchTerm: function (text, term) {
        if (!term) return text;
        const regex = new RegExp(`(${term.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&')})`, 'gi');
        return text.replace(regex, '<mark>$1</mark>');
    },

    /**
     * Initialize topic voting functionality
     */
    initTopicVoting: function () {
        const voteButtons = this.elements.voteButtons;
        if (!voteButtons || !voteButtons.length) return;

        voteButtons.forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const topicId = button.dataset.topicId;
                const voteType = button.dataset.voteType; // 'up' or 'down'

                if (!topicId) return;

                // Show loading state
                const originalHtml = button.innerHTML;
                button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span>';
                button.disabled = true;

                // Send vote via AJAX
                fetch(`${this.config.ajaxUrl}voteTopic`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: `topic_id=${topicId}&vote_type=${voteType}`
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update vote count
                            const countElement = document.querySelector(`.vote-count-${topicId}`);
                            if (countElement) {
                                countElement.textContent = data.votes;
                            }

                            // Add active class to button
                            button.classList.add('active');

                            // Remove active class from opposite button if it exists
                            const oppositeType = voteType === 'up' ? 'down' : 'up';
                            const oppositeButton = document.querySelector(`.topic-vote-btn[data-topic-id="${topicId}"][data-vote-type="${oppositeType}"]`);
                            if (oppositeButton) {
                                oppositeButton.classList.remove('active');
                            }

                            // Show notification
                            this.showNotification('Vote recorded successfully', 'success');
                        } else {
                            this.showNotification(data.message || 'Error recording vote', 'danger');
                        }
                    })
                    .catch(error => {
                        console.error('Voting error:', error);
                        this.showNotification('Error processing your vote', 'danger');
                    })
                    .finally(() => {
                        // Restore button state
                        button.innerHTML = originalHtml;
                        button.disabled = false;
                    });
            });
        });
    },

    /**
     * Setup quick reply functionality
     */
    setupQuickReply: function () {
        const replyForm = this.elements.replyForm;
        const replyButtons = this.elements.replyButtons;

        if (!replyForm) return;

        // Setup form submission via AJAX
        replyForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(replyForm);
            const submitBtn = replyForm.querySelector('button[type="submit"]');

            // Show loading state
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Posting...';
            submitBtn.disabled = true;

            fetch(replyForm.action, {
                method: 'POST',
                body: formData
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Add the new reply to the page without reloading
                        if (data.html) {
                            const repliesContainer = document.querySelector('.topic-replies');
                            if (repliesContainer) {
                                // Append new reply
                                repliesContainer.insertAdjacentHTML('beforeend', data.html);

                                // Scroll to new reply
                                const newReply = repliesContainer.lastElementChild;
                                if (newReply) {
                                    newReply.scrollIntoView({ behavior: 'smooth' });
                                    newReply.classList.add('highlight-new');
                                    setTimeout(() => {
                                        newReply.classList.remove('highlight-new');
                                    }, 3000);
                                }
                            }

                            // Clear form
                            this.elements.replyContent.value = '';

                            // Show success notification
                            this.showNotification('Reply posted successfully', 'success');
                        } else {
                            // If no HTML returned, reload the page
                            window.location.reload();
                        }
                    } else {
                        this.showNotification(data.message || 'Error posting reply', 'danger');
                    }
                })
                .catch(error => {
                    console.error('Reply error:', error);
                    this.showNotification('Error submitting your reply', 'danger');
                })
                .finally(() => {
                    // Restore button state
                    submitBtn.innerHTML = 'Post Reply';
                    submitBtn.disabled = false;
                });
        });

        // Setup quote reply functionality
        if (replyButtons && replyButtons.length) {
            replyButtons.forEach(button => {
                button.addEventListener('click', (e) => {
                    e.preventDefault();
                    const replyId = button.dataset.replyId;
                    const authorName = button.dataset.authorName;

                    if (replyId && this.elements.replyContent) {
                        // Get the content from the reply
                        const replyContent = document.querySelector(`.reply-content-${replyId}`);
                        if (replyContent) {
                            const quoteText = `> **${authorName} wrote:**\n> ${replyContent.textContent.trim().replace(/\n/g, '\n> ')}\n\n`;

                            // Insert at cursor position or append to end
                            this.insertTextAtCursor(this.elements.replyContent, quoteText);

                            // Focus the textarea
                            this.elements.replyContent.focus();

                            // Scroll to reply form
                            replyForm.scrollIntoView({ behavior: 'smooth' });
                        }
                    }
                });
            });
        }
    },

    /**
     * Initialize draft saving functionality for topic creation and replies
     */
    initDraftSaving: function () {
        const topicForm = document.querySelector('.create-topic-form');
        const replyContent = this.elements.replyContent;

        // Auto-save topic drafts
        if (topicForm) {
            const titleInput = topicForm.querySelector('input[name="title"]');
            const contentInput = topicForm.querySelector('textarea[name="content"]');
            const categorySelect = topicForm.querySelector('select[name="category_id"]');

            if (titleInput && contentInput) {
                // Load existing draft
                const topicDraft = JSON.parse(localStorage.getItem('forum_topic_draft') || '{}');

                if (topicDraft.title) titleInput.value = topicDraft.title;
                if (topicDraft.content) contentInput.value = topicDraft.content;
                if (topicDraft.category && categorySelect) categorySelect.value = topicDraft.category;

                // Setup auto-save
                const saveDraft = () => {
                    const draft = {
                        title: titleInput.value.trim(),
                        content: contentInput.value.trim(),
                        category: categorySelect ? categorySelect.value : null,
                        timestamp: new Date().getTime()
                    };

                    localStorage.setItem('forum_topic_draft', JSON.stringify(draft));
                };

                titleInput.addEventListener('input', this.debounce(saveDraft, this.config.debounceTime));
                contentInput.addEventListener('input', this.debounce(saveDraft, this.config.debounceTime));
                if (categorySelect) {
                    categorySelect.addEventListener('change', saveDraft);
                }

                // Clear draft when form is submitted
                topicForm.addEventListener('submit', () => {
                    localStorage.removeItem('forum_topic_draft');
                });
            }
        }

        // Auto-save reply drafts
        if (replyContent) {
            const topicId = new URLSearchParams(window.location.search).get('id') ||
                window.location.pathname.split('/').pop();

            // Only proceed if we have a topic ID
            if (topicId) {
                const draftKey = `forum_reply_draft_${topicId}`;

                // Load existing draft
                const replyDraft = localStorage.getItem(draftKey);
                if (replyDraft) {
                    replyContent.value = replyDraft;

                    // Add a restore draft message if content exists
                    if (replyDraft.trim().length > 0) {
                        const draftNotice = document.createElement('div');
                        draftNotice.className = 'alert alert-info alert-dismissible fade show mt-2';
                        draftNotice.innerHTML = `
                            <i class="bi bi-info-circle me-2"></i> 
                            A draft reply was restored.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        `;
                        replyContent.parentNode.insertBefore(draftNotice, replyContent.nextSibling);
                    }
                }

                // Setup auto-save
                replyContent.addEventListener('input', this.debounce(() => {
                    localStorage.setItem(draftKey, replyContent.value.trim());
                }, this.config.debounceTime));

                // Clear draft when reply form is submitted
                const replyForm = this.elements.replyForm;
                if (replyForm) {
                    replyForm.addEventListener('submit', () => {
                        localStorage.removeItem(draftKey);
                    });
                }
            }
        }
    },

    /**
     * Setup topic filtering and sorting functionality
     */
    setupTopicFilter: function () {
        const filterDropdown = this.elements.filterDropdown;
        const topicsList = this.elements.topicsList;

        if (!filterDropdown || !topicsList) return;

        const filterLinks = filterDropdown.querySelectorAll('.dropdown-item');
        filterLinks.forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();

                const filter = link.dataset.filter;
                const sort = link.dataset.sort;
                const categoryId = topicsList.dataset.categoryId || '';

                // Show loading state
                topicsList.innerHTML = `
                    <div class="text-center py-5">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3 text-muted">Loading topics...</p>
                    </div>
                `;

                // Update active filter in UI
                filterLinks.forEach(l => l.classList.remove('active'));
                link.classList.add('active');

                // Update filter button text
                const filterBtn = document.querySelector('.filter-btn-text');
                if (filterBtn) {
                    filterBtn.textContent = link.textContent.trim();
                }

                // Fetch filtered topics via AJAX
                fetch(`${this.config.ajaxUrl}getFilteredTopics?filter=${filter}&sort=${sort}&category_id=${categoryId}`)
                    .then(response => response.json())
                    .then(data => {
                        if (data.success && data.html) {
                            // Replace topics list with new HTML
                            topicsList.innerHTML = data.html;

                            // Initialize tooltips for new content
                            this.initTooltips();

                            // Update URL without reload
                            const url = new URL(window.location);
                            url.searchParams.set('filter', filter);
                            url.searchParams.set('sort', sort);
                            window.history.pushState({}, '', url);
                        } else {
                            this.showNotification('Error loading topics', 'danger');
                            topicsList.innerHTML = `
                                <div class="text-center py-5">
                                    <i class="bi bi-exclamation-circle text-danger fs-1"></i>
                                    <p class="mt-3 text-muted">Error loading topics. Please try again.</p>
                                    <button class="btn btn-primary btn-sm mt-2 retry-btn">Retry</button>
                                </div>
                            `;

                            // Add retry functionality
                            const retryBtn = topicsList.querySelector('.retry-btn');
                            if (retryBtn) {
                                retryBtn.addEventListener('click', () => link.click());
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Filter error:', error);
                        this.showNotification('Error loading topics', 'danger');
                        topicsList.innerHTML = `
                            <div class="text-center py-5">
                                <i class="bi bi-exclamation-circle text-danger fs-1"></i>
                                <p class="mt-3 text-muted">Error loading topics. Please try again.</p>
                                <button class="btn btn-primary btn-sm mt-2 retry-btn">Retry</button>
                            </div>
                        `;

                        // Add retry functionality
                        const retryBtn = topicsList.querySelector('.retry-btn');
                        if (retryBtn) {
                            retryBtn.addEventListener('click', () => link.click());
                        }
                    });
            });
        });
    },

    /**
     * Setup image preview for forum posts with attachments
     */
    setupImagePreview: function () {
        // Add click handler for image thumbnails
        document.addEventListener('click', (e) => {
            const thumbnail = e.target.closest('.forum-img-thumbnail');
            if (thumbnail) {
                e.preventDefault();

                const fullSizeUrl = thumbnail.getAttribute('href') || thumbnail.getAttribute('data-fullsize');
                if (!fullSizeUrl) return;

                // Create modal for image preview
                const modal = document.createElement('div');
                modal.className = 'modal fade';
                modal.id = 'imagePreviewModal';
                modal.setAttribute('tabindex', '-1');
                modal.setAttribute('aria-hidden', 'true');

                modal.innerHTML = `
                    <div class="modal-dialog modal-dialog-centered modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Image Preview</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body text-center p-0">
                                <img src="${fullSizeUrl}" class="img-fluid" alt="Full size image">
                            </div>
                            <div class="modal-footer">
                                <a href="${fullSizeUrl}" class="btn btn-primary btn-sm" download>
                                    <i class="bi bi-download me-1"></i> Download
                                </a>
                                <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                `;

                document.body.appendChild(modal);

                // Initialize and show the modal
                const modalInstance = new bootstrap.Modal(modal);
                modalInstance.show();

                // Remove modal from DOM after it's hidden
                modal.addEventListener('hidden.bs.modal', () => {
                    modal.remove();
                });
            }
        });
    },

    /**
     * Setup infinite scroll for topics list
     */
    setupInfiniteScroll: function () {
        const topicsList = this.elements.topicsList;
        if (!topicsList) return;

        // Check if pagination is needed
        const pagination = document.querySelector('.forum-pagination');
        if (!pagination) return;

        // Get current page and total pages
        const currentPage = parseInt(pagination.dataset.currentPage || '1');
        const totalPages = parseInt(pagination.dataset.totalPages || '1');

        // If there's only one page, no need for infinite scroll
        if (currentPage >= totalPages) return;

        // Create loading indicator
        const loadingIndicator = document.createElement('div');
        loadingIndicator.className = 'text-center py-4 loading-indicator d-none';
        loadingIndicator.innerHTML = `
            <div class="spinner-border spinner-border-sm text-primary" role="status">
                <span class="visually-hidden">Loading more topics...</span>
            </div>
            <p class="small text-muted mt-2 mb-0">Loading more topics...</p>
        `;

        // Append loading indicator
        topicsList.parentNode.appendChild(loadingIndicator);

        // Setup infinite scroll
        let nextPage = currentPage + 1;
        let loading = false;

        const loadMoreTopics = () => {
            if (loading || nextPage > totalPages) return;

            // Calculate scroll position to trigger load
            const scrollPosition = window.scrollY + window.innerHeight;
            const triggerPosition = document.body.offsetHeight - 500;

            if (scrollPosition < triggerPosition) return;

            loading = true;
            loadingIndicator.classList.remove('d-none');

            // Get current URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const filter = urlParams.get('filter') || '';
            const sort = urlParams.get('sort') || '';
            const category = urlParams.get('category') || topicsList.dataset.categoryId || '';

            // Fetch more topics via AJAX
            fetch(`${this.config.ajaxUrl}getMoreTopics?page=${nextPage}&filter=${filter}&sort=${sort}&category=${category}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success && data.html) {
                        // Append new topics
                        topicsList.insertAdjacentHTML('beforeend', data.html);

                        // Initialize tooltips for new content
                        this.initTooltips();

                        // Update current page
                        nextPage++;

                        // Hide pagination if we've reached the end
                        if (nextPage > totalPages) {
                            pagination.classList.add('d-none');
                            loadingIndicator.remove();
                        }
                    } else {
                        this.showNotification('Error loading more topics', 'danger');
                        loadingIndicator.innerHTML = `
                            <p class="text-danger mb-2">Error loading topics</p>
                            <button class="btn btn-sm btn-primary retry-load">Retry</button>
                        `;

                        // Add retry functionality
                        const retryBtn = loadingIndicator.querySelector('.retry-load');
                        if (retryBtn) {
                            retryBtn.addEventListener('click', () => {
                                loadingIndicator.classList.add('d-none');
                                loading = false;
                                loadMoreTopics();
                            });
                        }
                    }
                })
                .catch(error => {
                    console.error('Infinite scroll error:', error);
                    this.showNotification('Error loading more topics', 'danger');
                    loadingIndicator.innerHTML = `
                        <p class="text-danger mb-2">Error loading topics</p>
                        <button class="btn btn-sm btn-primary retry-load">Retry</button>
                    `;

                    // Add retry functionality
                    const retryBtn = loadingIndicator.querySelector('.retry-load');
                    if (retryBtn) {
                        retryBtn.addEventListener('click', () => {
                            loadingIndicator.classList.add('d-none');
                            loading = false;
                            loadMoreTopics();
                        });
                    }
                })
                .finally(() => {
                    loading = false;
                    if (nextPage <= totalPages) {
                        loadingIndicator.classList.add('d-none');
                    }
                });
        };

        // Add scroll event listener with throttling
        window.addEventListener('scroll', this.throttle(loadMoreTopics, 200));
    },

    /**
     * Initialize markdown editor for content creation
     */
    initMarkdownEditor: function () {
        const contentAreas = document.querySelectorAll('.markdown-editor');
        if (!contentAreas.length) return;

        contentAreas.forEach(textarea => {
            // Create toolbar
            const toolbar = document.createElement('div');
            toolbar.className = 'markdown-toolbar btn-toolbar mb-2';
            toolbar.setAttribute('role', 'toolbar');
            toolbar.setAttribute('aria-label', 'Formatting options');

            toolbar.innerHTML = `
                <div class="btn-group me-2" role="group" aria-label="Basic formatting">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-action="bold" title="Bold">
                        <i class="bi bi-type-bold"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-action="italic" title="Italic">
                        <i class="bi bi-type-italic"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-action="heading" title="Heading">
                        <i class="bi bi-type-h1"></i>
                    </button>
                </div>
                
                <div class="btn-group me-2" role="group" aria-label="Lists">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-action="unordered-list" title="Bullet List">
                        <i class="bi bi-list-ul"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-action="ordered-list" title="Numbered List">
                        <i class="bi bi-list-ol"></i>
                    </button>
                </div>
                
                <div class="btn-group me-2" role="group" aria-label="Links and media">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-action="link" title="Link">
                        <i class="bi bi-link-45deg"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-action="image" title="Image">
                        <i class="bi bi-image"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-action="code" title="Code">
                        <i class="bi bi-code"></i>
                    </button>
                </div>
                
                <div class="btn-group" role="group" aria-label="More options">
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-action="quote" title="Quote">
                        <i class="bi bi-chat-quote"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary" data-action="horizontal-rule" title="Horizontal Line">
                        <i class="bi bi-hr"></i>
                    </button>
                </div>
                
                <div class="markdown-help ms-auto">
                    <button type="button" class="btn btn-sm btn-outline-info" data-bs-toggle="modal" data-bs-target="#markdownHelpModal">
                        <i class="bi bi-question-circle"></i> Formatting Help
                    </button>
                </div>
            `;

            // Insert toolbar before textarea
            textarea.parentNode.insertBefore(toolbar, textarea);

            // Add event listeners to toolbar buttons
            toolbar.querySelectorAll('button[data-action]').forEach(button => {
                button.addEventListener('click', () => {
                    const action = button.getAttribute('data-action');
                    this.applyMarkdownFormatting(textarea, action);
                });
            });

            // Create preview tab functionality
            const previewContainer = document.createElement('div');
            previewContainer.className = 'markdown-preview-container mt-3 d-none';
            previewContainer.innerHTML = '<div class="markdown-preview p-3 border rounded bg-light"></div>';

            // Create toggle buttons
            const toggleContainer = document.createElement('div');
            toggleContainer.className = 'btn-group btn-group-sm mb-2';
            toggleContainer.innerHTML = `
                <button type="button" class="btn btn-primary active" data-view="write">
                    <i class="bi bi-pencil me-1"></i> Write
                </button>
                <button type="button" class="btn btn-outline-primary" data-view="preview">
                    <i class="bi bi-eye me-1"></i> Preview
                </button>
            `;

            // Insert toggle and preview after textarea
            textarea.parentNode.insertBefore(toggleContainer, textarea);
            textarea.parentNode.insertBefore(previewContainer, textarea.nextSibling);

            // Add event listeners to toggle buttons
            const writeBtn = toggleContainer.querySelector('[data-view="write"]');
            const previewBtn = toggleContainer.querySelector('[data-view="preview"]');
            const preview = previewContainer.querySelector('.markdown-preview');

            writeBtn.addEventListener('click', () => {
                writeBtn.classList.add('btn-primary');
                writeBtn.classList.remove('btn-outline-primary');
                previewBtn.classList.add('btn-outline-primary');
                previewBtn.classList.remove('btn-primary');

                textarea.classList.remove('d-none');
                previewContainer.classList.add('d-none');
            });

            previewBtn.addEventListener('click', () => {
                previewBtn.classList.add('btn-primary');
                previewBtn.classList.remove('btn-outline-primary');
                writeBtn.classList.add('btn-outline-primary');
                writeBtn.classList.remove('btn-primary');

                textarea.classList.add('d-none');
                previewContainer.classList.remove('d-none');

                // Convert markdown to HTML
                this.renderMarkdownPreview(textarea.value, preview);
            });

            // Add modal for markdown help if it doesn't exist
            if (!document.getElementById('markdownHelpModal')) {
                const helpModal = document.createElement('div');
                helpModal.className = 'modal fade';
                helpModal.id = 'markdownHelpModal';
                helpModal.setAttribute('tabindex', '-1');
                helpModal.setAttribute('aria-hidden', 'true');

                helpModal.innerHTML = `
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Markdown Formatting Guide</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <h6>Basic Formatting</h6>
                                        <table class="table table-sm table-bordered">
                                            <tr>
                                                <td><code>**Bold text**</code></td>
                                                <td><strong>Bold text</strong></td>
                                            </tr>
                                            <tr>
                                                <td><code>*Italic text*</code></td>
                                                <td><em>Italic text</em></td>
                                            </tr>
                                            <tr>
                                                <td><code>~~Strikethrough~~</code></td>
                                                <td><s>Strikethrough</s></td>
                                            </tr>
                                        </table>
                                        
                                        <h6 class="mt-3">Headings</h6>
                                        <table class="table table-sm table-bordered">
                                            <tr>
                                                <td><code># Heading 1</code></td>
                                                <td><h1 style="font-size: 1.5rem;">Heading 1</h1></td>
                                            </tr>
                                            <tr>
                                                <td><code>## Heading 2</code></td>
                                                <td><h2 style="font-size: 1.3rem;">Heading 2</h2></td>
                                            </tr>
                                        </table>
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <h6>Lists</h6>
                                        <table class="table table-sm table-bordered">
                                            <tr>
                                                <td>
                                                    <code>
                                                        * Item 1<br>
                                                        * Item 2
                                                    </code>
                                                </td>
                                                <td>
                                                    <ul class="mb-0">
                                                        <li>Item 1</li>
                                                        <li>Item 2</li>
                                                    </ul>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td>
                                                    <code>
                                                        1. First<br>
                                                        2. Second
                                                    </code>
                                                </td>
                                                <td>
                                                    <ol class="mb-0">
                                                        <li>First</li>
                                                        <li>Second</li>
                                                    </ol>
                                                </td>
                                            </tr>
                                        </table>
                                        
                                        <h6 class="mt-3">Links & Images</h6>
                                        <table class="table table-sm table-bordered">
                                            <tr>
                                                <td><code>[Link text](https://example.com)</code></td>
                                                <td><a href="#">Link text</a></td>
                                            </tr>
                                            <tr>
                                                <td><code>![Alt text](image-url.jpg)</code></td>
                                                <td>Displays an image</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                
                                <div class="mt-3">
                                    <h6>Code</h6>
                                    <table class="table table-sm table-bordered">
                                        <tr>
                                            <td><code>\`Inline code\`</code></td>
                                            <td><code>Inline code</code></td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <pre><code>\`\`\`
Code block
\`\`\`</code></pre>
                                            </td>
                                            <td>
                                                <pre><code>Code block</code></pre>
                                            </td>
                                        </tr>
                                    </table>
                                    
                                    <h6>Blockquotes</h6>
                                    <table class="table table-sm table-bordered">
                                        <tr>
                                            <td><code>> This is a quote</code></td>
                                            <td>
                                                <blockquote class="blockquote">
                                                    <p class="mb-0">This is a quote</p>
                                                </blockquote>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Got it</button>
                            </div>
                        </div>
                    </div>
                `;

                document.body.appendChild(helpModal);
            }
        });
    },

    /**
     * Apply markdown formatting to text in textarea
     * @param {HTMLElement} textarea - The textarea element
     * @param {string} action - The formatting action to apply
     */
    applyMarkdownFormatting: function (textarea, action) {
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const text = textarea.value;
        const beforeSelection = text.substring(0, start);
        const selection = text.substring(start, end);
        const afterSelection = text.substring(end);

        let replacement = '';

        switch (action) {
            case 'bold':
                replacement = `**${selection || 'bold text'}**`;
                break;
            case 'italic':
                replacement = `*${selection || 'italic text'}*`;
                break;
            case 'heading':
                replacement = `\n## ${selection || 'Heading'}\n`;
                break;
            case 'unordered-list':
                replacement = selection.split('\n').map(line => `* ${line}`).join('\n');
                break;
            case 'ordered-list':
                replacement = selection.split('\n').map((line, index) => `${index + 1}. ${line}`).join('\n');
                break;
            case 'link':
                replacement = `[${selection || 'link text'}](https://)`;
                break;
            case 'image':
                replacement = `![${selection || 'alt text'}](https://)`;
                break;
            case 'code':
                replacement = `\`${selection || 'code'}\``;
                break;
            case 'quote':
                replacement = selection.split('\n').map(line => `> ${line}`).join('\n');
                break;
            case 'horizontal-rule':
                replacement = '\n---\n';
                break;
            default:
                replacement = selection;
        }

        textarea.value = beforeSelection + replacement + afterSelection;

        // Set cursor position after the inserted text
        const newCursorPosition = beforeSelection.length + replacement.length;
        textarea.setSelectionRange(newCursorPosition, newCursorPosition);

        // Focus the textarea
        textarea.focus();
    },

    /**
     * Render markdown preview
     * @param {string} markdown - Markdown text
     * @param {HTMLElement} previewElement - Element to render preview in
     */
    renderMarkdownPreview: function (markdown, previewElement) {
        // Simple markdown conversion (for illustration purposes)
        // In a real app, use a library like marked.js or showdown
        let html = markdown
            // Escape HTML
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')

            // Headings
            .replace(/^### (.*$)/gm, '<h3>$1</h3>')
            .replace(/^## (.*$)/gm, '<h2>$1</h2>')
            .replace(/^# (.*$)/gm, '<h1>$1</h1>')

            // Bold and italic
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>')
            .replace(/\*(.*?)\*/g, '<em>$1</em>')

            // Strikethrough
            .replace(/~~(.*?)~~/g, '<s>$1</s>')

            // Links
            .replace(/\[(.*?)\]\((.*?)\)/g, '<a href="$2" target="_blank">$1</a>')

            // Images
            .replace(/!\[(.*?)\]\((.*?)\)/g, '<img src="$2" alt="$1" class="img-fluid">')

            // Lists (basic support)
            .replace(/^\* (.*$)/gm, '<ul><li>$1</li></ul>')
            .replace(/^[0-9]+\. (.*$)/gm, '<ol><li>$1</li></ol>')

            // Blockquotes
            .replace(/^> (.*$)/gm, '<blockquote class="blockquote"><p>$1</p></blockquote>')

            // Code blocks
            .replace(/```([\s\S]*?)```/g, '<pre><code>$1</code></pre>')

            // Inline code
            .replace(/`([^`]+)`/g, '<code>$1</code>')

            // Horizontal rule
            .replace(/^---$/gm, '<hr>')

            // Fix consecutive lists
            .replace(/<\/ul>\s*<ul>/g, '')
            .replace(/<\/ol>\s*<ol>/g, '')

            // Paragraphs
            .replace(/\n\n/g, '<br><br>');

        previewElement.innerHTML = html;
    },

    /**
     * Setup forum notifications
     */
    setupNotifications: function () {
        const notificationBell = this.elements.notificationBell;
        if (!notificationBell) return;

        // Check for new notifications periodically
        setInterval(() => {
            this.checkForNotifications();
        }, this.config.refreshInterval);

        // Initial check
        this.checkForNotifications();

        // Toggle notifications panel
        notificationBell.addEventListener('click', (e) => {
            e.preventDefault();
            const panel = document.querySelector('.notifications-panel');
            if (panel) {
                panel.classList.toggle('d-none');
            }
        });
    },

    /**
     * Check for new notifications
     */
    checkForNotifications: function () {
        const notificationBell = this.elements.notificationBell;
        if (!notificationBell) return;

        // Only check for logged-in users
        const isLoggedIn = document.body.classList.contains('user-logged-in');
        if (!isLoggedIn) return;

        fetch(`${this.config.ajaxUrl}checkNotifications`)
            .then(response => response.json())
            .then(data => {
                if (data.success && data.notifications.length > 0) {
                    // Update notification bell with count
                    notificationBell.classList.add('has-notifications');
                    notificationBell.dataset.count = data.notifications.length;

                    // Update notifications panel
                    const panel = document.querySelector('.notifications-panel');
                    if (panel) {
                        this.updateNotificationsPanel(panel, data.notifications);
                    }

                    // Play notification sound
                    this.playNotificationSound();
                } else {
                    notificationBell.classList.remove('has-notifications');
                    notificationBell.dataset.count = '';
                }
            })
            .catch(error => console.error('Notification check error:', error));
    },

    /**
     * Update notifications panel with new notifications
     * @param {HTMLElement} panel - Notifications panel element
     * @param {Array} notifications - Array of notification objects
     */
    updateNotificationsPanel: function (panel, notifications) {
        if (!notifications.length) {
            panel.innerHTML = '<div class="p-3 text-center text-muted">No new notifications</div>';
            return;
        }

        let html = '<div class="list-group list-group-flush">';

        notifications.forEach(notification => {
            html += `
                <a href="${notification.url}" class="list-group-item list-group-item-action ${notification.read ? '' : 'bg-light'}">
                    <div class="d-flex w-100 justify-content-between align-items-center">
                        <h6 class="mb-1">${notification.title}</h6>
                        <small class="text-muted">${notification.time_ago}</small>
                    </div>
                    <p class="mb-1 small">${notification.message}</p>
                </a>
            `;
        });

        html += '</div>';
        html += '<div class="text-center p-2"><a href="/notifications" class="btn btn-sm btn-primary">View All</a></div>';

        panel.innerHTML = html;
    },

    /**
     * Mark notifications as read
     */
    markNotificationsAsRead: function () {
        // Only for logged in users
        const isLoggedIn = document.body.classList.contains('user-logged-in');
        if (!isLoggedIn) return;

        fetch(`${this.config.ajaxUrl}markNotificationsAsRead`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update UI
                    const badge = document.querySelector('.notification-badge');
                    if (badge) {
                        badge.classList.add('d-none');
                    }
                }
            })
            .catch(error => console.error('Mark as read error:', error));
    },

    /**
     * Play notification sound
     */
    playNotificationSound: function () {
        // Check user preferences first
        const soundsEnabled = localStorage.getItem('forum_notification_sounds') !== 'disabled';
        if (!soundsEnabled) return;

        // Create audio element
        const audio = new Audio('/public/sounds/notification.mp3');
        audio.volume = 0.5;

        // Play sound
        audio.play().catch(error => {
            // Browser may block autoplay
            console.log('Notification sound blocked by browser', error);
        });
    },

    /**
     * Setup mobile navigation improvements
     */
    setupMobileNav: function () {
        const mobileNavToggle = this.elements.mobileNavToggle;
        if (!mobileNavToggle) return;

        // Add smooth scrolling for anchor links on mobile
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);

                if (targetElement) {
                    e.preventDefault();
                    targetElement.scrollIntoView({
                        behavior: 'smooth'
                    });

                    // Close mobile menu if open
                    const navbarCollapse = document.querySelector('.navbar-collapse.show');
                    if (navbarCollapse) {
                        const bsCollapse = new bootstrap.Collapse(navbarCollapse);
                        bsCollapse.hide();
                    }
                }
            });
        });
    },

    /**
     * Show a notification message
     * @param {string} message - The notification message
     * @param {string} type - The notification type (success, danger, warning, info)
     * @param {number} timeout - Auto-hide timeout in ms (0 for no auto-hide)
     */
    showNotification: function (message, type = 'info', timeout = 5000) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `toast align-items-center border-0 bg-${type} text-white`;
        notification.setAttribute('role', 'alert');
        notification.setAttribute('aria-live', 'assertive');
        notification.setAttribute('aria-atomic', 'true');

        notification.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    ${message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;

        // Create or get toast container
        let toastContainer = document.querySelector('.toast-container');
        if (!toastContainer) {
            toastContainer = document.createElement('div');
            toastContainer.className = 'toast-container position-fixed bottom-0 end-0 p-3';
            document.body.appendChild(toastContainer);
        }

        // Add notification to container
        toastContainer.appendChild(notification);

        // Initialize and show toast
        const toast = new bootstrap.Toast(notification, {
            autohide: timeout > 0,
            delay: timeout
        });
        toast.show();

        // Remove from DOM when hidden
        notification.addEventListener('hidden.bs.toast', () => {
            notification.remove();

            // Remove container if empty
            if (toastContainer.children.length === 0) {
                toastContainer.remove();
            }
        });
    },

    /**
     * Insert text at cursor position in a textarea
     * @param {HTMLElement} textarea - The textarea element
     * @param {string} text - Text to insert
     */
    insertTextAtCursor: function (textarea, text) {
        const start = textarea.selectionStart;
        const end = textarea.selectionEnd;
        const textareaValue = textarea.value;

        textarea.value = textareaValue.substring(0, start) + text + textareaValue.substring(end);

        // Move cursor after inserted text
        textarea.selectionStart = textarea.selectionEnd = start + text.length;
    },

    /**
     * Debounce function to limit how often a function is called
     * @param {function} func - Function to debounce
     * @param {number} wait - Wait time in milliseconds
     * @returns {function} Debounced function
     */
    debounce: function (func, wait) {
        let timeout;
        return function (...args) {
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(this, args), wait);
        };
    },

    /**
     * Throttle function to limit how often a function is called
     * @param {function} func - Function to throttle
     * @param {number} limit - Limit in milliseconds
     * @returns {function} Throttled function
     */
    throttle: function (func, limit) {
        let inThrottle;
        return function (...args) {
            if (!inThrottle) {
                func.apply(this, args);
                inThrottle = true;
                setTimeout(() => inThrottle = false, limit);
            }
        };
    },

    /**
     * Initialize the topic page functionality
     * @param {number} topicId - The current topic ID
     */
    initializeTopicPage: function (topicId) {
        console.log('Initializing topic page:', topicId);
        this.config.topicId = topicId;

        // Setup topic page specific functionality
        this.setupVoting();
        this.setupQuoteReplies();
        this.setupEditing();
        this.setupSorting();
        this.setupRichTextToolbar();
        this.setupPreviewButton();
        this.setupRemoveQuote();
        this.setupDeleteActions();
    },

    /**
     * Set up voting buttons for posts and replies
     */
    setupVoting: function () {
        document.querySelectorAll('.vote-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                if (button.classList.contains('disabled')) return;

                const postId = button.dataset.postId;
                const voteType = button.dataset.vote;
                const voteCountSpan = button.querySelector('.vote-count');
                const currentCount = parseInt(voteCountSpan?.textContent || '0');

                // Update UI optimistically
                if (voteCountSpan) {
                    voteCountSpan.textContent = currentCount + 1;
                }

                button.classList.add('active');
                button.disabled = true;

                // Send vote to server
                fetch(this.config.ajaxUrl + 'vote', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': this.config.csrfToken
                    },
                    body: JSON.stringify({
                        postId: postId,
                        voteType: voteType
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update the vote count with the accurate number from server
                            if (voteCountSpan) {
                                voteCountSpan.textContent = data.newCount;
                            }

                            // Disable the opposite vote button if needed
                            const oppositeType = voteType === 'up' ? 'down' : 'up';
                            const oppositeButton = document.querySelector(`.vote-btn[data-post-id="${postId}"][data-vote="${oppositeType}"]`);
                            if (oppositeButton) {
                                oppositeButton.classList.add('disabled');
                                oppositeButton.disabled = true;
                            }

                            this.showNotification('Vote recorded successfully', 'success');
                        } else {
                            // Revert the UI if there was an error
                            if (voteCountSpan) {
                                voteCountSpan.textContent = currentCount;
                            }

                            button.classList.remove('active');
                            button.disabled = false;

                            // Show error message
                            if (data.message === 'login_required') {
                                this.showLoginPrompt();
                            } else {
                                this.showNotification(data.message || 'Error voting', 'danger');
                            }
                        }
                    })
                    .catch(error => {
                        console.error('Error voting:', error);
                        // Revert the UI
                        if (voteCountSpan) {
                            voteCountSpan.textContent = currentCount;
                        }

                        button.classList.remove('active');
                        button.disabled = false;
                        this.showNotification('Error connecting to server', 'danger');
                    });
            });
        });
    },

    /**
     * Set up quote functionality for replies
     */
    setupQuoteReplies: function () {
        document.querySelectorAll('.quote-reply-btn').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const replyId = button.dataset.replyId;
                const author = button.dataset.author;
                const replyContent = document.querySelector(`.reply-content-${replyId}`)?.innerHTML;

                if (!replyContent) return;

                // Extract text content without HTML
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = replyContent;
                const textContent = tempDiv.textContent.trim();

                // Limit quote length
                const maxQuoteLength = 150;
                const quoteText = textContent.length > maxQuoteLength
                    ? textContent.substring(0, maxQuoteLength) + '...'
                    : textContent;

                // Show the quote in the reply form
                const quoteContainer = document.querySelector('.quick-reply-quote');
                if (quoteContainer) {
                    const authorEl = quoteContainer.querySelector('.quote-author');
                    const textEl = quoteContainer.querySelector('.quote-text');

                    if (authorEl) authorEl.textContent = author + ' wrote:';
                    if (textEl) textEl.textContent = quoteText;

                    quoteContainer.classList.remove('d-none');
                }

                // Set the full quote in the hidden field
                const quoteInput = document.getElementById('replyQuote');
                if (quoteInput) {
                    quoteInput.value = JSON.stringify({
                        author: author,
                        content: textContent,
                        replyId: replyId
                    });
                }

                // Scroll to reply form and focus
                const replyFormContainer = document.getElementById('reply-form-container');
                if (replyFormContainer) {
                    replyFormContainer.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    setTimeout(() => document.getElementById('replyContent')?.focus(), 600);
                }
            });
        });
    },

    /**
     * Set up reply editing functionality
     */
    setupEditing: function () {
        // Show edit form
        document.querySelectorAll('.edit-reply-action').forEach(button => {
            // Ensure buttons are visible and properly styled
            if (button.classList.contains('d-none')) {
                button.classList.remove('d-none');
            }

            // Make sure the button has the correct styling
            button.classList.add('btn', 'btn-sm', 'btn-outline-secondary');

            // Ensure icon is visible if it exists
            const icon = button.querySelector('.bi');
            if (icon) {
                button.innerHTML = `${icon.outerHTML} Edit`;
            }

            button.addEventListener('click', (e) => {
                e.preventDefault();
                const replyId = button.dataset.replyId;

                if (!replyId) {
                    console.error('No reply ID found for edit action');
                    return;
                }

                const contentContainer = document.querySelector(`.reply-content-${replyId}`);
                const editForm = document.querySelector(`.edit-reply-form-container-${replyId}`);

                if (contentContainer && editForm) {
                    contentContainer.classList.add('d-none');
                    editForm.classList.remove('d-none');
                } else {
                    console.error(`Could not find elements for reply ID ${replyId}`);
                }
            });
        });

        // Cancel edit
        document.querySelectorAll('.cancel-edit').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const replyId = button.dataset.replyId;
                const contentContainer = document.querySelector(`.reply-content-${replyId}`);
                const editForm = document.querySelector(`.edit-reply-form-container-${replyId}`);

                if (contentContainer && editForm) {
                    editForm.classList.add('d-none');
                    contentContainer.classList.remove('d-none');
                }
            });
        });

        // Submit edit
        document.querySelectorAll('.edit-reply-form').forEach(form => {
            form.addEventListener('submit', (e) => {
                e.preventDefault();
                const replyId = form.dataset.replyId;
                const contentTextarea = form.querySelector('.edit-reply-content');

                if (!contentTextarea) {
                    this.showNotification('Could not find content textarea', 'danger');
                    return;
                }

                const content = contentTextarea.value;

                if (content.trim().length < 10) {
                    this.showNotification('Reply content must be at least 10 characters', 'warning');
                    return;
                }

                // Disable form during submission
                const submitButton = form.querySelector('button[type="submit"]');
                const cancelButton = form.querySelector('.cancel-edit');

                if (submitButton) {
                    submitButton.disabled = true;
                    submitButton.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Saving...';
                }

                if (cancelButton) {
                    cancelButton.disabled = true;
                }

                // Construct the URL with the correct path
                const url = `${window.location.origin}/web/community/editReply/${replyId}`;

                // Send edit to server
                fetch(url, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        content: content
                    })
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update the displayed content
                            const contentContainer = document.querySelector(`.reply-content-${replyId}`);
                            if (contentContainer) {
                                contentContainer.innerHTML = data.formattedContent || content.replace(/\n/g, '<br>');
                            }

                            // Hide edit form
                            const editForm = document.querySelector(`.edit-reply-form-container-${replyId}`);
                            if (editForm && contentContainer) {
                                editForm.classList.add('d-none');
                                contentContainer.classList.remove('d-none');
                            }

                            this.showNotification('Reply updated successfully', 'success');
                        } else {
                            this.showNotification(data.message || 'Error updating reply', 'danger');
                        }
                    })
                    .catch(error => {
                        console.error('Error updating reply:', error);
                        this.showNotification('Error connecting to server', 'danger');
                    })
                    .finally(() => {
                        // Re-enable form buttons
                        if (submitButton) {
                            submitButton.disabled = false;
                            submitButton.innerHTML = '<i class="bi bi-save me-1"></i> Save Changes';
                        }

                        if (cancelButton) {
                            cancelButton.disabled = false;
                        }
                    });
            });
        });
    },

    /**
     * Set up sorting for replies
     */
    setupSorting: function () {
        document.querySelectorAll('.sort-replies').forEach(button => {
            button.addEventListener('click', function () {
                // Remove active class from all buttons
                document.querySelectorAll('.sort-replies').forEach(btn => {
                    btn.classList.remove('active');
                });

                // Add active class to clicked button
                this.classList.add('active');

                // Get all replies
                const repliesContainer = document.querySelector('.replies-container');
                const replies = Array.from(repliesContainer.querySelectorAll('.reply-item'));

                // Sort based on selected option
                const sortType = this.dataset.sort;

                if (sortType === 'newest') {
                    replies.sort((a, b) => parseInt(b.dataset.date) - parseInt(a.dataset.date));
                } else if (sortType === 'oldest') {
                    replies.sort((a, b) => parseInt(a.dataset.date) - parseInt(b.dataset.date));
                } else if (sortType === 'votes') {
                    replies.sort((a, b) => parseInt(b.dataset.votes) - parseInt(a.dataset.votes));
                }

                // Re-append in the new order
                replies.forEach(reply => {
                    repliesContainer.appendChild(reply);
                });
            });
        });
    },

    /**
     * Set up rich text toolbar buttons
     */
    setupRichTextToolbar: function () {
        document.querySelectorAll('.toolbar-btn').forEach(button => {
            button.addEventListener('click', function () {
                const tag = this.dataset.tag;
                const textarea = document.getElementById('replyContent');
                const start = textarea.selectionStart;
                const end = textarea.selectionEnd;
                const selectedText = textarea.value.substring(start, end);
                let replacement = '';

                // Define tag wrappings
                switch (tag) {
                    case 'bold':
                        replacement = `**${selectedText}**`;
                        break;
                    case 'italic':
                        replacement = `*${selectedText}*`;
                        break;
                    case 'link':
                        const url = prompt('Enter URL:', 'https://');
                        if (url) {
                            replacement = `[${selectedText || url}](${url})`;
                        } else {
                            return;
                        }
                        break;
                    case 'image':
                        const imgUrl = prompt('Enter image URL:', 'https://');
                        if (imgUrl) {
                            replacement = `![${selectedText || 'Image'}](${imgUrl})`;
                        } else {
                            return;
                        }
                        break;
                    case 'code':
                        if (selectedText.includes('\n')) {
                            replacement = `\`\`\`\n${selectedText}\n\`\`\``;
                        } else {
                            replacement = `\`${selectedText}\``;
                        }
                        break;
                    case 'quote':
                        replacement = selectedText.split('\n').map(line => `> ${line}`).join('\n');
                        break;
                }

                // Replace the selected text with the modified version
                textarea.value = textarea.value.substring(0, start) + replacement + textarea.value.substring(end);

                // Refocus on the textarea and place cursor at the end of the replaced text
                textarea.focus();
                textarea.selectionStart = start + replacement.length;
                textarea.selectionEnd = start + replacement.length;
            });
        });
    },

    /**
     * Set up preview button for reply form
     */
    setupPreviewButton: function () {
        const previewBtn = document.querySelector('.preview-btn');
        if (previewBtn) {
            previewBtn.addEventListener('click', function () {
                const content = document.getElementById('replyContent').value;
                const previewArea = document.querySelector('.preview-area');
                const previewContent = previewArea.querySelector('.preview-content');

                if (content.trim().length === 0) {
                    Forum.showNotification('Nothing to preview', 'warning');
                    return;
                }

                // Toggle preview visibility
                if (previewArea.classList.contains('d-none')) {
                    // Render preview
                    previewContent.innerHTML = '<div class="spinner-border spinner-border-sm text-secondary" role="status"><span class="visually-hidden">Loading...</span></div> Generating preview...';
                    previewArea.classList.remove('d-none');

                    // Send markdown to server for rendering
                    fetch(Forum.config.ajaxUrl + 'renderMarkdown', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            content: content
                        })
                    })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                previewContent.innerHTML = data.html;
                            } else {
                                previewContent.innerHTML = '<div class="text-danger">Error generating preview</div>';
                            }
                        })
                        .catch(error => {
                            console.error('Error rendering preview:', error);
                            previewContent.innerHTML = '<div class="text-danger">Error connecting to server</div>';
                        });

                    // Update button text
                    previewBtn.innerHTML = '<i class="bi bi-pencil me-1"></i> Edit';
                } else {
                    // Hide preview
                    previewArea.classList.add('d-none');
                    previewBtn.innerHTML = '<i class="bi bi-eye me-1"></i> Preview';
                }
            });
        }
    },

    /**
     * Set up remove quote button
     */
    setupRemoveQuote: function () {
        const removeQuoteBtn = document.getElementById('removeQuote');
        if (removeQuoteBtn) {
            removeQuoteBtn.addEventListener('click', function () {
                document.querySelector('.quick-reply-quote').classList.add('d-none');
                document.getElementById('replyQuote').value = '';
            });
        }
    },

    /**
     * Set up delete actions for topics and replies
     */
    setupDeleteActions: function () {
        // Make sure action buttons are properly styled and visible
        document.querySelectorAll('.post-actions, .reply-actions').forEach(actionContainer => {
            // Ensure action containers are visible and have proper styling
            actionContainer.classList.add('d-flex', 'align-items-center', 'gap-2');

            // Remove any unwanted classes that might hide the container
            if (actionContainer.classList.contains('d-none')) {
                actionContainer.classList.remove('d-none');
            }
        });

        // Fix all action buttons
        document.querySelectorAll('.edit-reply-action, .quote-reply-btn, .delete-reply-action, .mark-solution-action').forEach(button => {
            // Ensure the button is visible
            if (button.classList.contains('d-none')) {
                button.classList.remove('d-none');
            }

            // Add proper styling classes
            if (button.tagName.toLowerCase() === 'button' || button.tagName.toLowerCase() === 'a') {
                button.classList.add('btn', 'btn-sm');

                // Add appropriate color classes based on action type
                if (button.classList.contains('edit-reply-action')) {
                    button.classList.add('btn-outline-secondary');
                } else if (button.classList.contains('delete-reply-action')) {
                    button.classList.add('btn-outline-danger');
                } else if (button.classList.contains('quote-reply-btn')) {
                    button.classList.add('btn-outline-primary');
                } else if (button.classList.contains('mark-solution-action')) {
                    button.classList.add('btn-outline-success');
                }
            }

            // Make sure icons are visible and button has text
            const icon = button.querySelector('.bi');
            if (icon && button.textContent.trim() === '') {
                const title = button.getAttribute('title') || '';
                const actionType = button.classList.contains('edit-reply-action') ? 'Edit' :
                    button.classList.contains('delete-reply-action') ? 'Delete' :
                        button.classList.contains('quote-reply-btn') ? 'Quote' :
                            button.classList.contains('mark-solution-action') ? 'Mark as Solution' : title;

                button.innerHTML = `${icon.outerHTML} ${actionType}`;
            }
        });

        // Topic delete button click
        const deleteTopicBtn = document.querySelector('#deleteTopic');
        if (deleteTopicBtn) {
            deleteTopicBtn.addEventListener('click', (e) => {
                e.preventDefault();
                const topicId = deleteTopicBtn.dataset.topicId;
                const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
                const deleteModalBody = document.getElementById('deleteModalBody');

                if (deleteModalBody) {
                    deleteModalBody.textContent = 'Are you sure you want to delete this topic? All replies will be permanently removed. This action cannot be undone.';
                }

                // Set up the confirm button action
                const confirmBtn = document.getElementById('confirmDeleteBtn');
                if (confirmBtn) {
                    confirmBtn.onclick = function () {
                        window.location.href = Forum.config.ajaxUrl + 'deleteTopic/' + topicId;
                    };
                }

                deleteModal.show();
            });
        }

        // Reply delete button
        document.querySelectorAll('.delete-reply-action').forEach(button => {
            button.addEventListener('click', (e) => {
                e.preventDefault();
                const replyId = button.dataset.replyId;
                const deleteModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));
                const deleteModalBody = document.getElementById('deleteModalBody');

                if (deleteModalBody) {
                    deleteModalBody.textContent = 'Are you sure you want to delete this reply? This action cannot be undone.';
                }

                // Set up the confirm button action
                const confirmBtn = document.getElementById('confirmDeleteBtn');
                if (confirmBtn) {
                    confirmBtn.onclick = function () {
                        window.location.href = Forum.config.ajaxUrl + 'deleteReply/' + replyId;
                    };
                }

                deleteModal.show();
            });
        });

        // Reset confirm button action when modal is hidden
        const deleteConfirmModal = document.getElementById('deleteConfirmModal');
        if (deleteConfirmModal) {
            deleteConfirmModal.addEventListener('hide.bs.modal', function () {
                const confirmBtn = document.getElementById('confirmDeleteBtn');
                if (confirmBtn) {
                    confirmBtn.onclick = null;
                }
            });
        }
    },

    /**
     * Show login prompt when user needs to be logged in
     */
    showLoginPrompt: function () {
        // Create modal if it doesn't exist
        let loginModal = document.getElementById('loginPromptModal');

        if (!loginModal) {
            const modalHTML = `
                <div class="modal fade" id="loginPromptModal" tabindex="-1" aria-labelledby="loginPromptModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="loginPromptModalLabel">Login Required</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <p>You need to be logged in to perform this action.</p>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                <a href="${this.config.ajaxUrl}/../users/login?redirect=${encodeURIComponent(window.location.href)}" class="btn btn-primary">Login</a>
                            </div>
                        </div>
                    </div>
                </div>
            `;

            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = modalHTML;
            document.body.appendChild(tempDiv.firstChild);
            loginModal = document.getElementById('loginPromptModal');
        }

        // Show the modal
        const modal = new bootstrap.Modal(loginModal);
        modal.show();
    }
};