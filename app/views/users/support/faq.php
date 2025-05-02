<?php require APPROOT . '/views/layouts/header.php'; ?>

<section class="faq-hero">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6 animate-fade-in">
                <h1 class="display-4 fw-bold mb-3">Frequently Asked <span class="text-gradient">Questions</span></h1>
                <p class="lead text-muted mb-4">Find answers to the most common questions about our platform and services.</p>

                <!-- Improved FAQ search with autocomplete -->
                <div class="faq-search-container mb-4">
                    <form class="faq-search-form" action="#" method="GET">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0">
                                <i class="fas fa-search text-muted"></i>
                            </span>
                            <input type="text" class="form-control border-start-0 py-3" id="faqSearch" placeholder="Search questions and answers..." aria-label="Search FAQs">
                            <button class="btn btn-primary" type="button" id="searchButton">
                                Search
                            </button>
                        </div>
                        <div id="searchSuggestions" class="search-suggestions shadow-sm"></div>
                    </form>
                    <!-- Search results indicator -->
                    <div id="searchResults" class="search-results-info mt-2 d-none">
                        <span class="badge bg-primary">Results found: <span id="resultCount">0</span></span>
                        <button class="btn btn-sm btn-link" id="clearSearch">Clear search</button>
                    </div>
                </div>

                <!-- FAQ Categories Pills for quick navigation on mobile -->
                <div class="faq-categories-pills d-block d-lg-none mb-4">
                    <div class="d-flex flex-wrap gap-2">
                        <a href="#general" class="badge rounded-pill bg-primary py-2 px-3 text-decoration-none category-pill active">General</a>
                        <a href="#freelancer" class="badge rounded-pill bg-light text-dark py-2 px-3 text-decoration-none category-pill">Freelancers</a>
                        <a href="#client" class="badge rounded-pill bg-light text-dark py-2 px-3 text-decoration-none category-pill">Clients</a>
                        <a href="#technical" class="badge rounded-pill bg-light text-dark py-2 px-3 text-decoration-none category-pill">Technical</a>
                        <a href="#billing" class="badge rounded-pill bg-light text-dark py-2 px-3 text-decoration-none category-pill">Billing</a>
                    </div>
                </div>

                <!-- Quick actions -->
                <div class="quick-actions">
                    <span class="text-muted me-2">Quick links:</span>
                    <a href="#general" class="badge rounded-pill bg-light text-dark me-2 mb-2 py-2 px-3 quick-link">General</a>
                    <a href="#freelancer" class="badge rounded-pill bg-light text-dark me-2 mb-2 py-2 px-3 quick-link">Freelancers</a>
                    <a href="#client" class="badge rounded-pill bg-light text-dark me-2 mb-2 py-2 px-3 quick-link">Clients</a>
                    <a href="#technical" class="badge rounded-pill bg-light text-dark me-2 mb-2 py-2 px-3 quick-link">Technical</a>
                    <a href="#billing" class="badge rounded-pill bg-light text-dark mb-2 py-2 px-3 quick-link">Billing</a>
                </div>
            </div>
            <div class="col-lg-6 animate-fade-in-left">
                <div class="faq-hero-image">
                    <img src="<?php echo URLROOT; ?>/public/images/faq-hero.svg" alt="FAQ Illustration" class="img-fluid">
                </div>
            </div>
        </div>
    </div>
</section>

<section class="faq-content">
    <div class="container">
        <div class="row">
            <div class="col-lg-3 mb-4 mb-lg-0">
                <!-- Sticky category navigation -->
                <div class="faq-nav-sticky">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-primary text-white">
                            <h5 class="mb-0"><i class="fas fa-list me-2"></i> Categories</h5>
                        </div>
                        <div class="list-group list-group-flush faq-categories">
                            <a href="#general" class="list-group-item list-group-item-action d-flex align-items-center active">
                                <i class="fas fa-circle-info me-3"></i>
                                <span>General</span>
                                <span class="badge bg-primary rounded-pill ms-auto">4</span>
                            </a>
                            <a href="#freelancer" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="fas fa-user-tie me-3"></i>
                                <span>Freelancers</span>
                                <span class="badge bg-primary rounded-pill ms-auto">4</span>
                            </a>
                            <a href="#client" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="fas fa-building me-3"></i>
                                <span>Clients</span>
                                <span class="badge bg-primary rounded-pill ms-auto">4</span>
                            </a>
                            <a href="#technical" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="fas fa-gear me-3"></i>
                                <span>Technical</span>
                                <span class="badge bg-primary rounded-pill ms-auto">4</span>
                            </a>
                            <a href="#billing" class="list-group-item list-group-item-action d-flex align-items-center">
                                <i class="fas fa-credit-card me-3"></i>
                                <span>Billing</span>
                                <span class="badge bg-primary rounded-pill ms-auto">4</span>
                            </a>
                        </div>
                    </div>

                    <!-- Help box -->
                    <div class="card shadow-sm border-0 mt-4">
                        <div class="card-body bg-primary-light">
                            <div class="d-flex align-items-center">
                                <div class="help-icon me-3">
                                    <i class="fas fa-headset fa-2x text-primary"></i>
                                </div>
                                <div>
                                    <h5 class="mb-1">Need more help?</h5>
                                    <p class="small mb-2">We're here to assist you</p>
                                    <a href="<?php echo URLROOT; ?>/support/create" class="btn btn-sm btn-primary">Contact Support</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-9">
                <!-- FAQ sections -->
                <div class="faq-sections">
                    <!-- General section -->
                    <div id="general" class="faq-section mb-5 reveal">
                        <div class="faq-section-header">
                            <div class="d-flex align-items-center">
                                <div class="faq-icon me-3">
                                    <div class="icon-circle bg-primary-light">
                                        <i class="fas fa-circle-info text-primary"></i>
                                    </div>
                                </div>
                                <div>
                                    <h2 class="mb-0">General Information</h2>
                                    <p class="text-muted">Basic information about the LenSI platform</p>
                                </div>
                            </div>
                        </div>

                        <div class="accordion custom-accordion mt-4" id="accordionGeneral">
                            <?php foreach ($data['categories']['general'] as $index => $faq): ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="general-heading-<?php echo $index; ?>">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#general-collapse-<?php echo $index; ?>" aria-expanded="false"
                                            aria-controls="general-collapse-<?php echo $index; ?>">
                                            <i class="fas fa-circle-question me-3 text-primary"></i>
                                            <?php echo $faq['question']; ?>
                                        </button>
                                    </h2>
                                    <div id="general-collapse-<?php echo $index; ?>" class="accordion-collapse collapse"
                                        aria-labelledby="general-heading-<?php echo $index; ?>"
                                        data-bs-parent="#accordionGeneral">
                                        <div class="accordion-body">
                                            <div class="faq-answer">
                                                <?php echo $faq['answer']; ?>
                                            </div>
                                            <div class="faq-feedback mt-3">
                                                <p class="small text-muted mb-2">Was this answer helpful?</p>
                                                <button class="btn btn-sm btn-outline-success me-2" onclick="rateAnswer(this, 'helpful')">
                                                    <i class="fas fa-thumbs-up me-1"></i> Yes
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" onclick="rateAnswer(this, 'not-helpful')">
                                                    <i class="fas fa-thumbs-down me-1"></i> No
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Freelancer section -->
                    <div id="freelancer" class="faq-section mb-5 reveal">
                        <div class="faq-section-header">
                            <div class="d-flex align-items-center">
                                <div class="faq-icon me-3">
                                    <div class="icon-circle bg-success-light">
                                        <i class="fas fa-user-tie text-success"></i>
                                    </div>
                                </div>
                                <div>
                                    <h2 class="mb-0">For Freelancers</h2>
                                    <p class="text-muted">Information for freelance professionals using our platform</p>
                                </div>
                            </div>
                        </div>

                        <div class="accordion custom-accordion mt-4" id="accordionFreelancer">
                            <?php foreach ($data['categories']['freelancer'] as $index => $faq): ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="freelancer-heading-<?php echo $index; ?>">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#freelancer-collapse-<?php echo $index; ?>" aria-expanded="false"
                                            aria-controls="freelancer-collapse-<?php echo $index; ?>">
                                            <i class="fas fa-circle-question me-3 text-primary"></i>
                                            <?php echo $faq['question']; ?>
                                        </button>
                                    </h2>
                                    <div id="freelancer-collapse-<?php echo $index; ?>" class="accordion-collapse collapse"
                                        aria-labelledby="freelancer-heading-<?php echo $index; ?>"
                                        data-bs-parent="#accordionFreelancer">
                                        <div class="accordion-body">
                                            <div class="faq-answer">
                                                <?php echo $faq['answer']; ?>
                                            </div>
                                            <div class="faq-feedback mt-3">
                                                <p class="small text-muted mb-2">Was this answer helpful?</p>
                                                <button class="btn btn-sm btn-outline-success me-2" onclick="rateAnswer(this, 'helpful')">
                                                    <i class="fas fa-thumbs-up me-1"></i> Yes
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" onclick="rateAnswer(this, 'not-helpful')">
                                                    <i class="fas fa-thumbs-down me-1"></i> No
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Client section -->
                    <div id="client" class="faq-section mb-5 reveal">
                        <div class="faq-section-header">
                            <div class="d-flex align-items-center">
                                <div class="faq-icon me-3">
                                    <div class="icon-circle bg-warning-light">
                                        <i class="fas fa-building text-warning"></i>
                                    </div>
                                </div>
                                <div>
                                    <h2 class="mb-0">For Clients</h2>
                                    <p class="text-muted">Information for clients hiring on our platform</p>
                                </div>
                            </div>
                        </div>

                        <div class="accordion custom-accordion mt-4" id="accordionClient">
                            <?php foreach ($data['categories']['client'] as $index => $faq): ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="client-heading-<?php echo $index; ?>">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#client-collapse-<?php echo $index; ?>" aria-expanded="false"
                                            aria-controls="client-collapse-<?php echo $index; ?>">
                                            <i class="fas fa-circle-question me-3 text-primary"></i>
                                            <?php echo $faq['question']; ?>
                                        </button>
                                    </h2>
                                    <div id="client-collapse-<?php echo $index; ?>" class="accordion-collapse collapse"
                                        aria-labelledby="client-heading-<?php echo $index; ?>"
                                        data-bs-parent="#accordionClient">
                                        <div class="accordion-body">
                                            <div class="faq-answer">
                                                <?php echo $faq['answer']; ?>
                                            </div>
                                            <div class="faq-feedback mt-3">
                                                <p class="small text-muted mb-2">Was this answer helpful?</p>
                                                <button class="btn btn-sm btn-outline-success me-2" onclick="rateAnswer(this, 'helpful')">
                                                    <i class="fas fa-thumbs-up me-1"></i> Yes
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" onclick="rateAnswer(this, 'not-helpful')">
                                                    <i class="fas fa-thumbs-down me-1"></i> No
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Technical section -->
                    <div id="technical" class="faq-section mb-5 reveal">
                        <div class="faq-section-header">
                            <div class="d-flex align-items-center">
                                <div class="faq-icon me-3">
                                    <div class="icon-circle bg-info-light">
                                        <i class="fas fa-gear text-info"></i>
                                    </div>
                                </div>
                                <div>
                                    <h2 class="mb-0">Technical Support</h2>
                                    <p class="text-muted">Account and platform technical questions</p>
                                </div>
                            </div>
                        </div>

                        <div class="accordion custom-accordion mt-4" id="accordionTechnical">
                            <?php foreach ($data['categories']['technical'] as $index => $faq): ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="technical-heading-<?php echo $index; ?>">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#technical-collapse-<?php echo $index; ?>" aria-expanded="false"
                                            aria-controls="technical-collapse-<?php echo $index; ?>">
                                            <i class="fas fa-circle-question me-3 text-primary"></i>
                                            <?php echo $faq['question']; ?>
                                        </button>
                                    </h2>
                                    <div id="technical-collapse-<?php echo $index; ?>" class="accordion-collapse collapse"
                                        aria-labelledby="technical-heading-<?php echo $index; ?>"
                                        data-bs-parent="#accordionTechnical">
                                        <div class="accordion-body">
                                            <div class="faq-answer">
                                                <?php echo $faq['answer']; ?>
                                            </div>
                                            <div class="faq-feedback mt-3">
                                                <p class="small text-muted mb-2">Was this answer helpful?</p>
                                                <button class="btn btn-sm btn-outline-success me-2" onclick="rateAnswer(this, 'helpful')">
                                                    <i class="fas fa-thumbs-up me-1"></i> Yes
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" onclick="rateAnswer(this, 'not-helpful')">
                                                    <i class="fas fa-thumbs-down me-1"></i> No
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Billing section -->
                    <div id="billing" class="faq-section mb-5 reveal">
                        <div class="faq-section-header">
                            <div class="d-flex align-items-center">
                                <div class="faq-icon me-3">
                                    <div class="icon-circle bg-danger-light">
                                        <i class="fas fa-credit-card text-danger"></i>
                                    </div>
                                </div>
                                <div>
                                    <h2 class="mb-0">Billing & Payments</h2>
                                    <p class="text-muted">Information about payments, billing and refunds</p>
                                </div>
                            </div>
                        </div>

                        <div class="accordion custom-accordion mt-4" id="accordionBilling">
                            <?php foreach ($data['categories']['billing'] as $index => $faq): ?>
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="billing-heading-<?php echo $index; ?>">
                                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#billing-collapse-<?php echo $index; ?>" aria-expanded="false"
                                            aria-controls="billing-collapse-<?php echo $index; ?>">
                                            <i class="fas fa-circle-question me-3 text-primary"></i>
                                            <?php echo $faq['question']; ?>
                                        </button>
                                    </h2>
                                    <div id="billing-collapse-<?php echo $index; ?>" class="accordion-collapse collapse"
                                        aria-labelledby="billing-heading-<?php echo $index; ?>"
                                        data-bs-parent="#accordionBilling">
                                        <div class="accordion-body">
                                            <div class="faq-answer">
                                                <?php echo $faq['answer']; ?>
                                            </div>
                                            <div class="faq-feedback mt-3">
                                                <p class="small text-muted mb-2">Was this answer helpful?</p>
                                                <button class="btn btn-sm btn-outline-success me-2" onclick="rateAnswer(this, 'helpful')">
                                                    <i class="fas fa-thumbs-up me-1"></i> Yes
                                                </button>
                                                <button class="btn btn-sm btn-outline-danger" onclick="rateAnswer(this, 'not-helpful')">
                                                    <i class="fas fa-thumbs-down me-1"></i> No
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>

                <!-- Still need help section -->
                <div class="still-need-help mt-5 p-4 bg-light rounded-4 shadow-sm reveal">
                    <div class="row align-items-center">
                        <div class="col-lg-9">
                            <h3 class="mb-2">Still have questions?</h3>
                            <p class="mb-lg-0">If you couldn't find what you're looking for, our support team is here to help.</p>
                        </div>
                        <div class="col-lg-3 text-lg-end mt-3 mt-lg-0">
                            <a href="<?php echo URLROOT; ?>/support/create" class="btn btn-primary">Contact Support</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- FAQ specific styles - enhanced for better user experience -->
<style>
    /* Hero section with gradient */
    .faq-hero {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 5rem 0 3rem;
        margin-bottom: 3rem;
        position: relative;
        overflow: hidden;
    }

    .faq-hero::before {
        content: '';
        position: absolute;
        top: -10%;
        right: -5%;
        width: 300px;
        height: 300px;
        border-radius: 50%;
        background: rgba(10, 17, 40, 0.03);
        z-index: 0;
    }

    .faq-hero::after {
        content: '';
        position: absolute;
        bottom: -15%;
        left: -5%;
        width: 400px;
        height: 400px;
        border-radius: 50%;
        background: rgba(10, 17, 40, 0.03);
        z-index: 0;
    }

    .text-gradient {
        background: linear-gradient(90deg, var(--support-primary, #4C0070), #9C27B0);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        color: transparent;
    }

    /* FAQ Navigation */
    .faq-nav-sticky {
        position: sticky;
        top: 100px;
    }

    .faq-categories .list-group-item {
        border-left: 3px solid transparent;
        padding: 0.75rem 1rem;
        transition: all 0.3s ease;
    }

    .faq-categories .list-group-item:hover {
        background-color: #f8f9fa;
        border-left-color: var(--support-primary, #4C0070);
    }

    .faq-categories .list-group-item.active {
        background-color: rgba(76, 0, 112, 0.1);
        border-left-color: var(--support-primary, #4C0070);
        color: var(--support-primary, #4C0070);
        font-weight: 600;
    }

    /* Section Icons */
    .faq-icon .icon-circle {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 60px;
        height: 60px;
        border-radius: 50%;
        font-size: 1.5rem;
    }

    /* Custom accordion styling */
    .custom-accordion {
        border-radius: 0.5rem;
        overflow: hidden;
    }

    .custom-accordion .accordion-item {
        border: none;
        border-radius: 0.5rem !important;
        margin-bottom: 1rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
        transition: all 0.3s ease;
    }
    
    .custom-accordion .accordion-item:hover {
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        transform: translateY(-2px);
    }

    .custom-accordion .accordion-button {
        padding: 1.25rem 1.5rem;
        font-weight: 600;
        color: #1e293b;
        background-color: #ffffff;
        border: none;
        box-shadow: none;
        border-radius: 0.5rem !important;
    }

    .custom-accordion .accordion-button:not(.collapsed) {
        color: var(--support-primary, #4C0070);
        background-color: rgba(76, 0, 112, 0.05);
        box-shadow: none;
    }

    .custom-accordion .accordion-button:focus {
        box-shadow: none;
        border-color: transparent;
    }

    .custom-accordion .accordion-button::after {
        width: 1.25rem;
        height: 1.25rem;
        background-size: 1.25rem;
        transition: all 0.3s ease;
    }

    .custom-accordion .accordion-body {
        padding: 1.5rem;
        background-color: #f8f9fa;
        border-bottom-left-radius: 0.5rem;
        border-bottom-right-radius: 0.5rem;
    }

    /* Animation for search */
    .faq-search-container {
        transition: all 0.3s ease;
        position: relative;
    }

    .faq-search-container:focus-within {
        transform: translateY(-5px);
    }

    .faq-search-form input {
        padding: 1rem 1.5rem;
        font-size: 1rem;
    }
    
    /* Search suggestions dropdown */
    .search-suggestions {
        position: absolute;
        width: 100%;
        max-height: 300px;
        overflow-y: auto;
        background: white;
        border-radius: 0 0 0.5rem 0.5rem;
        z-index: 100;
        display: none;
    }
    
    .search-suggestions.show {
        display: block;
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        border: 1px solid #e9ecef;
        border-top: none;
    }
    
    .search-suggestion-item {
        padding: 0.75rem 1.25rem;
        cursor: pointer;
        border-bottom: 1px solid #f1f1f1;
    }
    
    .search-suggestion-item:hover,
    .search-suggestion-item:focus {
        background-color: #f8f9fa;
    }
    
    .search-suggestion-item:last-child {
        border-bottom: none;
    }
    
    /* Search results info */
    .search-results-info {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    
    /* Category pills for mobile */
    .faq-categories-pills {
        overflow-x: auto;
        white-space: nowrap;
        -webkit-overflow-scrolling: touch;
        padding-bottom: 0.5rem;
        scrollbar-width: thin;
    }
    
    .faq-categories-pills::-webkit-scrollbar {
        height: 4px;
    }
    
    .faq-categories-pills::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 10px;
    }
    
    .faq-categories-pills::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 10px;
    }
    
    .category-pill {
        display: inline-block;
        transition: all 0.3s ease;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    .category-pill.active {
        background-color: var(--support-primary, #4C0070) !important;
        color: white !important;
    }
    
    .category-pill:hover:not(.active) {
        background-color: rgba(76, 0, 112, 0.1) !important;
        color: var(--support-primary, #4C0070) !important;
    }

    /* Quick links styling */
    .quick-link {
        transition: all 0.3s ease;
        text-decoration: none;
        border: 1px solid #e9ecef;
    }

    .quick-link:hover {
        background-color: var(--support-primary, #4C0070);
        color: white !important;
        transform: translateY(-3px);
    }

    /* Animated reveal for sections */
    .reveal {
        opacity: 0;
        transform: translateY(30px);
        transition: all 0.8s ease;
        transition-delay: var(--delay, 0s);
    }

    .reveal.active {
        opacity: 1;
        transform: translateY(0);
    }

    /* Feedback animation */
    .faq-feedback button {
        transition: all 0.2s ease;
    }

    .faq-feedback button.selected-helpful {
        background-color: #10b981;
        color: white;
        border-color: #10b981;
    }

    .faq-feedback button.selected-not-helpful {
        background-color: #ef4444;
        color: white;
        border-color: #ef4444;
    }
    
    .feedback-message {
        transition: opacity 0.3s ease;
    }

    /* Responsive fixes */
    @media (max-width: 992px) {
        .faq-nav-sticky {
            position: relative;
            top: 0;
            margin-bottom: 2rem;
        }
        
        .faq-section {
            scroll-margin-top: 120px;
        }
    }

    /* Highlight search results */
    .highlight-match {
        background-color: rgba(76, 0, 112, 0.15);
        padding: 0.1rem 0.2rem;
        border-radius: 0.2rem;
        font-weight: 600;
        color: var(--support-primary, #4C0070);
    }
    
    /* Background colors */
    .bg-primary-light {
        background-color: rgba(76, 0, 112, 0.1);
    }

    .bg-success-light {
        background-color: rgba(16, 185, 129, 0.1);
    }

    .bg-warning-light {
        background-color: rgba(245, 158, 11, 0.1);
    }

    .bg-info-light {
        background-color: rgba(59, 130, 246, 0.1);
    }
    
    .bg-danger-light {
        background-color: rgba(239, 68, 68, 0.1);
    }
</style>

<!-- FAQ specific scripts - enhanced for better interactivity -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize reveal animations
        const revealElements = document.querySelectorAll('.reveal');
        
        // Add delay to stagger animation
        revealElements.forEach((element, index) => {
            element.style.setProperty('--delay', `${0.1 * index}s`);
        });

        const revealOnScroll = function() {
            revealElements.forEach(element => {
                const windowHeight = window.innerHeight;
                const elementTop = element.getBoundingClientRect().top;
                const elementVisible = 150;

                if (elementTop < windowHeight - elementVisible) {
                    element.classList.add('active');
                }
            });
        };

        // Run once to reveal elements in view on load
        revealOnScroll();

        // Add event listener
        window.addEventListener('scroll', revealOnScroll);

        // Smooth scrolling for anchor links
        const anchorLinks = document.querySelectorAll('a[href^="#"]');

        anchorLinks.forEach(anchorLink => {
            anchorLink.addEventListener('click', function(e) {
                e.preventDefault();

                // Update active category in sidebar
                document.querySelectorAll('.faq-categories .list-group-item').forEach(item => {
                    item.classList.remove('active');
                });
                
                // Update active pill for mobile
                document.querySelectorAll('.category-pill').forEach(pill => {
                    pill.classList.remove('active');
                    pill.classList.add('bg-light');
                    pill.classList.add('text-dark');
                    pill.classList.remove('bg-primary');
                });
                
                // Set the clicked item to active
                const href = this.getAttribute('href');
                document.querySelectorAll(`.faq-categories a[href="${href}"]`).forEach(item => {
                    item.classList.add('active');
                });
                
                // Set the clicked pill to active
                document.querySelectorAll(`.category-pill[href="${href}"]`).forEach(pill => {
                    pill.classList.add('active');
                    pill.classList.remove('bg-light');
                    pill.classList.remove('text-dark');
                });

                const targetId = this.getAttribute('href');
                const targetElement = document.querySelector(targetId);

                // Additional offset for navbar
                const offset = 120;
                const targetPosition = targetElement.getBoundingClientRect().top + window.pageYOffset - offset;

                window.scrollTo({
                    top: targetPosition,
                    behavior: 'smooth'
                });
            });
        });

        // Enhanced search functionality
        const searchInput = document.getElementById('faqSearch');
        const searchButton = document.getElementById('searchButton');
        const searchResults = document.getElementById('searchResults');
        const resultCount = document.getElementById('resultCount');
        const clearSearchBtn = document.getElementById('clearSearch');
        const searchSuggestions = document.getElementById('searchSuggestions');
        
        // Get all questions for suggestions
        const questions = [];
        document.querySelectorAll('.accordion-button').forEach(button => {
            questions.push({
                text: button.textContent.trim(),
                element: button
            });
        });
        
        // Suggestion functionality
        searchInput.addEventListener('input', function() {
            const query = this.value.toLowerCase().trim();
            
            if (query.length < 2) {
                searchSuggestions.classList.remove('show');
                return;
            }
            
            // Filter questions that match the query
            const matches = questions.filter(question => 
                question.text.toLowerCase().includes(query)
            ).slice(0, 5); // Limit to 5 suggestions
            
            if (matches.length > 0) {
                // Create and show suggestions
                searchSuggestions.innerHTML = '';
                matches.forEach(match => {
                    const item = document.createElement('div');
                    item.className = 'search-suggestion-item';
                    item.textContent = match.text;
                    item.tabIndex = 0; // Make focusable
                    
                    // Click handler to select suggestion
                    item.addEventListener('click', function() {
                        searchInput.value = match.text;
                        searchSuggestions.classList.remove('show');
                        performSearch();
                        
                        // Scroll to and open the matched accordion item
                        match.element.click();
                        match.element.scrollIntoView({
                            behavior: 'smooth',
                            block: 'center'
                        });
                    });
                    
                    // Keyboard navigation
                    item.addEventListener('keydown', function(e) {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            this.click();
                        }
                    });
                    
                    searchSuggestions.appendChild(item);
                });
                
                searchSuggestions.classList.add('show');
            } else {
                searchSuggestions.classList.remove('show');
            }
        });
        
        // Close suggestions when clicking outside
        document.addEventListener('click', function(e) {
            if (!e.target.closest('.faq-search-container')) {
                searchSuggestions.classList.remove('show');
            }
        });

        const performSearch = () => {
            const searchTerm = searchInput.value.toLowerCase().trim();
            let matchCount = 0;

            if (searchTerm.length < 2) {
                // Reset all highlights and display all sections
                resetSearch();
                searchResults.classList.add('d-none');
                return;
            }

            // Loop through all questions and highlight matches
            document.querySelectorAll('.accordion-item').forEach(item => {
                const questionButton = item.querySelector('.accordion-button');
                const answerBody = item.querySelector('.faq-answer');
                const questionText = questionButton.textContent.trim();
                const answerText = answerBody.textContent.trim();
                
                // Check if the search term exists in question or answer
                const matchesQuestion = questionText.toLowerCase().includes(searchTerm);
                const matchesAnswer = answerText.toLowerCase().includes(searchTerm);

                if (matchesQuestion || matchesAnswer) {
                    matchCount++;
                    
                    // Open the accordion panel
                    const accordionCollapse = item.querySelector('.accordion-collapse');
                    accordionCollapse.classList.add('show');
                    questionButton.classList.remove('collapsed');
                    questionButton.setAttribute('aria-expanded', 'true');

                    // Highlight question if it matches
                    if (matchesQuestion) {
                        // Preserve icon
                        const originalHTML = questionButton.innerHTML;
                        const iconMatch = originalHTML.match(/<i.*?<\/i>/);
                        const icon = iconMatch ? iconMatch[0] + ' ' : '';
                        
                        // Get text without icon
                        let textContent = questionText;
                        
                        // Highlight matching text
                        const regex = new RegExp(`(${searchTerm.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
                        const highlightedText = textContent.replace(regex, '<span class="highlight-match">$1</span>');
                        
                        // Replace content with highlighted version
                        questionButton.innerHTML = icon + highlightedText;
                    }
                    
                    // Highlight answer if it matches
                    if (matchesAnswer) {
                        const regex = new RegExp(`(${searchTerm.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
                        answerBody.innerHTML = answerBody.innerHTML.replace(regex, '<span class="highlight-match">$1</span>');
                    }
                    
                    // Show the relevant category section if hidden
                    const parentSection = item.closest('.faq-section');
                    if (parentSection) {
                        parentSection.style.display = 'block';
                        
                        // If this is the first match, scroll to it
                        if (matchCount === 1) {
                            setTimeout(() => {
                                item.scrollIntoView({
                                    behavior: 'smooth',
                                    block: 'center'
                                });
                            }, 300);
                        }
                    }
                } else {
                    // Close the accordion panel
                    const accordionCollapse = item.querySelector('.accordion-collapse');
                    accordionCollapse.classList.remove('show');
                    questionButton.classList.add('collapsed');
                    questionButton.setAttribute('aria-expanded', 'false');
                }
            });
            
            // Update results info
            if (matchCount > 0) {
                resultCount.textContent = matchCount;
                searchResults.classList.remove('d-none');
            } else {
                searchResults.classList.remove('d-none');
                resultCount.textContent = '0';
            }
        };

        // Reset search function
        function resetSearch() {
            // Reset all highlights in questions
            document.querySelectorAll('.accordion-button').forEach(button => {
                const originalText = button.textContent.trim();
                const iconMatch = button.innerHTML.match(/<i.*?<\/i>/);
                const icon = iconMatch ? iconMatch[0] + ' ' : '';
                button.innerHTML = icon + originalText;
            });
            
            // Reset all highlights in answers
            document.querySelectorAll('.faq-answer').forEach(answer => {
                answer.innerHTML = answer.innerHTML.replace(/<span class="highlight-match">(.*?)<\/span>/g, '$1');
            });
            
            // Reset accordion state if needed
            document.querySelectorAll('.accordion-collapse.show').forEach(panel => {
                panel.classList.remove('show');
                const button = panel.previousElementSibling.querySelector('button');
                if (button) {
                    button.classList.add('collapsed');
                    button.setAttribute('aria-expanded', 'false');
                }
            });
            
            // Show all sections
            document.querySelectorAll('.faq-section').forEach(section => {
                section.style.display = 'block';
            });
        }

        // Search input event listeners
        searchInput.addEventListener('input', function(e) {
            if (this.value.trim().length >= 2) {
                performSearch();
            } else if (this.value.trim().length === 0) {
                resetSearch();
                searchResults.classList.add('d-none');
            }
        });
        
        // Search button event listener
        searchButton.addEventListener('click', performSearch);
        
        // Clear search button
        clearSearchBtn.addEventListener('click', function() {
            searchInput.value = '';
            resetSearch();
            searchResults.classList.add('d-none');
            searchInput.focus();
        });
        
        // Enter key in search input
        searchInput.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                performSearch();
                searchSuggestions.classList.remove('show');
            }
        });

        // Update category navigation highlighting on scroll
        window.addEventListener('scroll', function() {
            const sections = document.querySelectorAll('.faq-section');
            let currentSection = '';

            sections.forEach(section => {
                const sectionTop = section.offsetTop;
                const sectionHeight = section.clientHeight;

                if (pageYOffset >= sectionTop - 200) {
                    currentSection = section.getAttribute('id');
                }
            });

            if (currentSection) {
                // Update sidebar navigation
                document.querySelectorAll('.faq-categories .list-group-item').forEach(item => {
                    item.classList.remove('active');
                    if (item.getAttribute('href').substring(1) === currentSection) {
                        item.classList.add('active');
                    }
                });
                
                // Update mobile pills
                document.querySelectorAll('.category-pill').forEach(pill => {
                    pill.classList.remove('active');
                    pill.classList.add('bg-light');
                    pill.classList.add('text-dark');
                    pill.classList.remove('bg-primary');
                    
                    if (pill.getAttribute('href').substring(1) === currentSection) {
                        pill.classList.add('active');
                        pill.classList.remove('bg-light');
                        pill.classList.remove('text-dark');
                    }
                });
            }
        });
        
        // Mobile category pill click handler
        document.querySelectorAll('.category-pill').forEach(pill => {
            pill.addEventListener('click', function(e) {
                // Remove active class from all pills
                document.querySelectorAll('.category-pill').forEach(p => {
                    p.classList.remove('active');
                    p.classList.add('bg-light');
                    p.classList.add('text-dark');
                    p.classList.remove('bg-primary');
                });
                
                // Add active class to clicked pill
                this.classList.add('active');
                this.classList.remove('bg-light');
                this.classList.remove('text-dark');
            });
        });
    });

    // Answer rating functionality
    function rateAnswer(button, ratingType) {
        // First, remove any selection from both buttons
        const parentFeedback = button.closest('.faq-feedback');
        parentFeedback.querySelectorAll('button').forEach(btn => {
            btn.classList.remove('selected-helpful', 'selected-not-helpful');
        });

        // Then add the appropriate class to the clicked button
        if (ratingType === 'helpful') {
            button.classList.add('selected-helpful');
            showFeedbackMessage(parentFeedback, 'Thank you for your feedback!', 'success');
        } else {
            button.classList.add('selected-not-helpful');
            showFeedbackMessage(parentFeedback, 'We\'ll work on improving this answer.', 'warning');
            
            // Show additional feedback form for not helpful answers
            setTimeout(() => {
                showImprovementForm(parentFeedback);
            }, 500);
        }

        // Here you could send the rating to the server
        // Example: sendRatingToServer(questionId, ratingType);
    }

    function showFeedbackMessage(parentElement, message, type) {
        // Remove any existing feedback message
        const existingMessage = parentElement.querySelector('.feedback-message');
        if (existingMessage) {
            existingMessage.remove();
        }

        // Create a new message
        const feedbackMessage = document.createElement('div');
        feedbackMessage.className = `feedback-message alert alert-${type} mt-2 py-2 px-3 small`;
        feedbackMessage.innerHTML = message;
        parentElement.appendChild(feedbackMessage);

        // Auto remove after 3 seconds
        setTimeout(() => {
            feedbackMessage.style.opacity = '0';
            setTimeout(() => {
                feedbackMessage.remove();
            }, 300);
        }, 3000);
    }
    
    function showImprovementForm(parentElement) {
        // Check if form already exists
        if (parentElement.querySelector('.improvement-form')) {
            return;
        }
        
        // Create improvement feedback form
        const formContainer = document.createElement('div');
        formContainer.className = 'improvement-form mt-3';
        formContainer.innerHTML = `
            <p class="small text-muted mb-2">How can we improve this answer?</p>
            <div class="mb-3">
                <textarea class="form-control form-control-sm" rows="2" placeholder="Please tell us why this answer wasn't helpful..."></textarea>
            </div>
            <div class="text-end">
                <button type="button" class="btn btn-sm btn-primary submit-feedback">Submit Feedback</button>
            </div>
        `;
        
        // Add submit handler
        const submitBtn = formContainer.querySelector('.submit-feedback');
        submitBtn.addEventListener('click', function() {
            const textarea = formContainer.querySelector('textarea');
            if (textarea.value.trim()) {
                // Here you would send the feedback to the server
                // For now, just show a thank you message
                formContainer.innerHTML = `<div class="alert alert-success py-2 px-3 small">Thank you for your detailed feedback!</div>`;
                
                // Remove after a few seconds
                setTimeout(() => {
                    formContainer.style.opacity = '0';
                    setTimeout(() => {
                        formContainer.remove();
                    }, 300);
                }, 3000);
            }
        });
        
        parentElement.appendChild(formContainer);
    }
</script>

<?php require APPROOT . '/views/layouts/footer.php'; ?>