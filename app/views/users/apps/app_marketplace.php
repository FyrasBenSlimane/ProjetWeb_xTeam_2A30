<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="freelancer-indicator">
    <div class="indicator-badge">
        <i class="fas fa-laptop-code"></i> Freelancer Account
    </div>
</div>

<main class="main-container">
    <section class="page-header-section">
        <div class="container">
            <div class="row">
                <div class="col-12">
                    <h1 class="page-title"><?php echo $data['title']; ?></h1>
                    <p class="page-description"><?php echo $data['description']; ?></p>
                </div>
            </div>
        </div>
    </section>

    <section class="apps-section py-4">
        <div class="container">
            <!-- Search and Filter -->
            <div class="row mb-4">
                <div class="col-md-8">
                    <div class="apps-search">
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-search"></i></span>
                            <input type="text" class="form-control" placeholder="Search for apps...">
                            <button class="btn btn-search" type="button">Search</button>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="apps-filter">
                        <button class="btn-filter">
                            <i class="fas fa-filter me-2"></i> Filter <span class="filter-count">0</span>
                        </button>
                        <select class="form-select" aria-label="Sort by">
                            <option selected>Sort by: Relevance</option>
                            <option value="1">Most Popular</option>
                            <option value="2">Newest First</option>
                            <option value="3">Top Rated</option>
                        </select>
                    </div>
                </div>
            </div>

            <!-- Featured Apps Section -->
            <div class="row mb-5">
                <div class="col-12 mb-4">
                    <div class="section-header">
                        <h3 class="section-title">Featured Apps</h3>
                        <a href="#" class="view-all-link">View all apps</a>
                    </div>
                </div>

                <?php foreach ($data['featured_apps'] as $app): ?>
                    <div class="col-lg-4 col-md-6 mb-4">
                        <div class="app-card">
                            <div class="app-icon">
                                <i class="<?php echo $app['icon']; ?>"></i>
                            </div>
                            <div class="app-info">
                                <h4 class="app-name"><?php echo $app['name']; ?></h4>
                                <div class="app-meta">
                                    <div class="app-rating">
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star"></i>
                                        <i class="fas fa-star-half-alt"></i>
                                        <span class="rating-count">(124)</span>
                                    </div>
                                    <div class="app-status <?php echo $app['status']; ?>-status">
                                        <?php echo ucfirst($app['status']); ?>
                                    </div>
                                </div>
                                <p class="app-description"><?php echo $app['description']; ?></p>
                                <div class="app-actions">
                                    <button class="btn-app-install">
                                        <?php echo $app['status'] == 'free' ? 'Install' : 'Buy Now'; ?>
                                    </button>
                                    <button class="btn-app-details">
                                        <i class="fas fa-info-circle"></i> Details
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Special Offers -->
            <div class="row mb-5">
                <div class="col-12 mb-4">
                    <div class="section-header">
                        <h3 class="section-title">Special Offers</h3>
                        <a href="#" class="view-all-link">View all offers</a>
                    </div>
                </div>

                <?php foreach ($data['special_offers'] as $offer): ?>
                    <div class="col-md-6 mb-4">
                        <div class="offer-card">
                            <div class="offer-content">
                                <h4 class="offer-title"><?php echo $offer['title']; ?></h4>
                                <p class="offer-description"><?php echo $offer['description']; ?></p>
                                <div class="offer-meta">
                                    <div class="offer-expiry">
                                        <i class="far fa-clock me-1"></i>
                                        <?php echo $offer['expires'] == 'Ongoing' ? 'Ongoing offer' : 'Expires ' . $offer['expires']; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="offer-action">
                                <button class="btn-claim-offer">
                                    Claim Offer
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- App Categories -->
            <div class="row mb-5">
                <div class="col-12 mb-4">
                    <div class="section-header">
                        <h3 class="section-title">Browse Categories</h3>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                        <h5 class="category-name">Business Tools</h5>
                        <p class="category-count">12 apps</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-clock"></i>
                        </div>
                        <h5 class="category-name">Time Tracking</h5>
                        <p class="category-count">8 apps</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-file-invoice-dollar"></i>
                        </div>
                        <h5 class="category-name">Invoicing</h5>
                        <p class="category-count">6 apps</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-project-diagram"></i>
                        </div>
                        <h5 class="category-name">Project Management</h5>
                        <p class="category-count">9 apps</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-robot"></i>
                        </div>
                        <h5 class="category-name">AI Assistants</h5>
                        <p class="category-count">7 apps</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-pencil-ruler"></i>
                        </div>
                        <h5 class="category-name">Design Tools</h5>
                        <p class="category-count">10 apps</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-lightbulb"></i>
                        </div>
                        <h5 class="category-name">Proposal Writing</h5>
                        <p class="category-count">4 apps</p>
                    </div>
                </div>

                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="category-card">
                        <div class="category-icon">
                            <i class="fas fa-graduation-cap"></i>
                        </div>
                        <h5 class="category-name">Learning</h5>
                        <p class="category-count">15 apps</p>
                    </div>
                </div>
            </div>

            <!-- My Apps -->
            <div class="row">
                <div class="col-12 mb-4">
                    <div class="section-header">
                        <h3 class="section-title">My Apps</h3>
                        <a href="#" class="view-all-link">Manage my apps</a>
                    </div>
                </div>

                <div class="col-12">
                    <div class="content-card">
                        <div class="my-apps-empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-cubes"></i>
                            </div>
                            <h5 class="empty-state-title">You don't have any apps yet</h5>
                            <p class="empty-state-text">Explore our app marketplace to find tools that can help you be more productive and successful in your freelance business.</p>
                            <button class="btn-explore-apps">
                                <i class="fas fa-search me-2"></i> Explore Apps
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<style>
    /* Page Header Styles */
    .page-header-section {
        background-color: var(--white);
        padding: 2rem 0 1.5rem;
        border-bottom: 1px solid var(--gray-200);
        margin-top: 70px;
        /* For fixed navbar */
    }

    .page-title {
        font-size: 1.75rem;
        font-weight: var(--font-weight-bold);
        color: var(--secondary);
        margin-bottom: 0.5rem;
    }

    .page-description {
        font-size: 1rem;
        color: var(--gray-600);
    }

    /* Search and Filter */
    .apps-search .input-group {
        box-shadow: var(--shadow-sm);
        border-radius: var(--border-radius-md);
        overflow: hidden;
    }

    .apps-search .input-group-text {
        background-color: var(--white);
        border: 1px solid var(--gray-300);
        border-right: none;
        padding: 0.65rem 1rem;
    }

    .apps-search .form-control {
        border: 1px solid var(--gray-300);
        border-left: none;
        border-right: none;
        padding: 0.65rem 1rem;
        font-size: 0.95rem;
    }

    .apps-search .form-control:focus {
        box-shadow: none;
        border-color: var(--gray-400);
    }

    .apps-search .btn-search {
        background-color: #14a800;
        color: var(--white);
        border: none;
        padding: 0.65rem 1.25rem;
        font-size: 0.95rem;
        font-weight: var(--font-weight-medium);
    }

    .apps-search .btn-search:hover {
        background-color: #0e7400;
    }

    .apps-filter {
        display: flex;
        gap: 1rem;
        justify-content: flex-end;
    }

    .btn-filter {
        background-color: var(--white);
        border: 1px solid var(--gray-300);
        border-radius: var(--border-radius-md);
        padding: 0.65rem 1.25rem;
        font-size: 0.95rem;
        color: var(--secondary);
        cursor: pointer;
        transition: all var(--transition-normal);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-filter:hover {
        background-color: var(--gray-100);
    }

    .filter-count {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 20px;
        height: 20px;
        border-radius: var(--border-radius-circle);
        background-color: var(--gray-300);
        color: var(--secondary);
        font-size: 0.8rem;
        font-weight: var(--font-weight-medium);
    }

    .form-select {
        border: 1px solid var(--gray-300);
        border-radius: var(--border-radius-md);
        padding: 0.65rem 1rem;
        font-size: 0.95rem;
        color: var(--secondary);
        cursor: pointer;
        width: auto;
    }

    .form-select:focus {
        box-shadow: none;
        border-color: var(--gray-400);
    }

    /* Section Headers */
    .section-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
    }

    .section-title {
        font-size: 1.5rem;
        font-weight: var(--font-weight-bold);
        color: var(--secondary);
        margin: 0;
    }

    .view-all-link {
        font-size: 0.95rem;
        color: #14a800;
        text-decoration: none;
        transition: all var(--transition-fast);
        font-weight: var(--font-weight-medium);
    }

    .view-all-link:hover {
        color: #0e7400;
        text-decoration: underline;
    }

    /* App Cards */
    .app-card {
        background-color: var(--white);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        display: flex;
        height: 100%;
        transition: all var(--transition-normal);
    }

    .app-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }

    .app-icon {
        width: 80px;
        height: 80px;
        flex-shrink: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        background: linear-gradient(135deg, #14a800, #0e7400);
        color: var(--white);
        font-size: 2rem;
        margin: 1.5rem;
        border-radius: var(--border-radius-md);
    }

    .app-info {
        padding: 1.5rem 1.5rem 1.5rem 0;
        flex-grow: 1;
        display: flex;
        flex-direction: column;
    }

    .app-name {
        font-size: 1.25rem;
        font-weight: var(--font-weight-semibold);
        color: var(--secondary);
        margin-bottom: 0.5rem;
    }

    .app-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 0.75rem;
    }

    .app-rating {
        color: #f39c12;
        font-size: 0.85rem;
    }

    .rating-count {
        color: var(--gray-600);
        font-size: 0.8rem;
        margin-left: 0.25rem;
    }

    .app-status {
        font-size: 0.8rem;
        font-weight: var(--font-weight-medium);
        padding: 0.25rem 0.75rem;
        border-radius: var(--border-radius-md);
    }

    .free-status {
        background-color: rgba(20, 168, 0, 0.1);
        color: #14a800;
    }

    .premium-status {
        background-color: rgba(155, 89, 182, 0.1);
        color: #9b59b6;
    }

    .app-description {
        font-size: 0.95rem;
        color: var(--gray-700);
        margin-bottom: 1rem;
        line-height: 1.5;
        flex-grow: 1;
    }

    .app-actions {
        display: flex;
        gap: 0.75rem;
    }

    .btn-app-install {
        background-color: #14a800;
        color: var(--white);
        border: none;
        border-radius: var(--border-radius-md);
        padding: 0.6rem 1.25rem;
        font-size: 0.9rem;
        font-weight: var(--font-weight-medium);
        cursor: pointer;
        transition: all var(--transition-normal);
    }

    .btn-app-install:hover {
        background-color: #0e7400;
    }

    .btn-app-details {
        background-color: transparent;
        border: 1px solid var(--gray-300);
        border-radius: var(--border-radius-md);
        padding: 0.6rem 1rem;
        font-size: 0.9rem;
        color: var(--secondary);
        cursor: pointer;
        transition: all var(--transition-normal);
    }

    .btn-app-details:hover {
        background-color: var(--gray-100);
    }

    /* Offer Cards */
    .offer-card {
        background-color: var(--white);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
        display: flex;
        height: 100%;
        transition: all var(--transition-normal);
    }

    .offer-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }

    .offer-content {
        padding: 1.5rem;
        flex-grow: 1;
    }

    .offer-title {
        font-size: 1.25rem;
        font-weight: var(--font-weight-semibold);
        color: var(--secondary);
        margin-bottom: 0.75rem;
    }

    .offer-description {
        font-size: 0.95rem;
        color: var(--gray-700);
        margin-bottom: 1rem;
        line-height: 1.5;
    }

    .offer-meta {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .offer-expiry {
        font-size: 0.85rem;
        color: var(--gray-600);
    }

    .offer-action {
        padding: 1.5rem 1.5rem 1.5rem 0;
        display: flex;
        align-items: center;
    }

    .btn-claim-offer {
        background-color: #9b59b6;
        color: var(--white);
        border: none;
        border-radius: var(--border-radius-md);
        padding: 0.6rem 1.25rem;
        font-size: 0.9rem;
        font-weight: var(--font-weight-medium);
        cursor: pointer;
        transition: all var(--transition-normal);
        white-space: nowrap;
    }

    .btn-claim-offer:hover {
        background-color: #8e44ad;
    }

    /* Category Cards */
    .category-card {
        background-color: var(--white);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-sm);
        padding: 1.5rem;
        text-align: center;
        height: 100%;
        transition: all var(--transition-normal);
        cursor: pointer;
    }

    .category-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }

    .category-icon {
        width: 60px;
        height: 60px;
        border-radius: var(--border-radius-circle);
        background-color: rgba(20, 168, 0, 0.1);
        color: #14a800;
        font-size: 1.5rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1rem;
        transition: all var(--transition-normal);
    }

    .category-card:hover .category-icon {
        background-color: #14a800;
        color: var(--white);
    }

    .category-name {
        font-size: 1.1rem;
        font-weight: var(--font-weight-semibold);
        color: var(--secondary);
        margin-bottom: 0.5rem;
    }

    .category-count {
        font-size: 0.9rem;
        color: var(--gray-600);
        margin: 0;
    }

    /* My Apps Empty State */
    .content-card {
        background-color: var(--white);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .my-apps-empty-state {
        text-align: center;
        padding: 3rem 2rem;
    }

    .empty-state-icon {
        width: 80px;
        height: 80px;
        border-radius: var(--border-radius-circle);
        background-color: rgba(20, 168, 0, 0.1);
        color: #14a800;
        font-size: 2rem;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 1.5rem;
    }

    .empty-state-title {
        font-size: 1.25rem;
        font-weight: var(--font-weight-semibold);
        color: var(--secondary);
        margin-bottom: 0.75rem;
    }

    .empty-state-text {
        font-size: 1rem;
        color: var(--gray-600);
        margin-bottom: 1.5rem;
        max-width: 600px;
        margin-left: auto;
        margin-right: auto;
    }

    .btn-explore-apps {
        background-color: #14a800;
        color: var(--white);
        border: none;
        border-radius: var(--border-radius-md);
        padding: 0.75rem 1.5rem;
        font-size: 0.95rem;
        font-weight: var(--font-weight-medium);
        cursor: pointer;
        transition: all var(--transition-normal);
        display: inline-flex;
        align-items: center;
    }

    .btn-explore-apps:hover {
        background-color: #0e7400;
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    /* Responsive Adjustments */
    @media (max-width: 992px) {
        .app-card {
            flex-direction: column;
        }

        .app-icon {
            width: 60px;
            height: 60px;
            font-size: 1.5rem;
            margin: 1.5rem auto 0;
        }

        .app-info {
            padding: 1.5rem;
            text-align: center;
        }

        .app-meta {
            flex-direction: column;
            gap: 0.5rem;
        }

        .offer-card {
            flex-direction: column;
        }

        .offer-action {
            padding: 0 1.5rem 1.5rem;
        }

        .btn-claim-offer {
            width: 100%;
        }
    }

    @media (max-width: 768px) {
        .page-title {
            font-size: 1.5rem;
        }

        .apps-filter {
            margin-top: 1rem;
            justify-content: space-between;
        }

        .btn-filter,
        .form-select {
            flex-grow: 1;
        }

        .app-actions {
            flex-direction: column;
        }
    }
</style>

<script>
<?php echo "
    document.addEventListener('DOMContentLoaded', function() {
        // Search button functionality
        const searchBtn = document.querySelector('.btn-search');
        if (searchBtn) {
            searchBtn.addEventListener('click', function() {
                const searchValue = document.querySelector('.apps-search input').value;
                alert('This would search for apps matching \"' + searchValue + '\" in a real application.');
            });
        }

        // Filter button functionality
        const filterBtn = document.querySelector('.btn-filter');
        if (filterBtn) {
            filterBtn.addEventListener('click', function() {
                alert('This would open app filters in a real application.');
            });
        }

        // Sort select functionality
        const sortSelect = document.querySelector('.form-select');
        if (sortSelect) {
            sortSelect.addEventListener('change', function() {
                alert('This would sort apps by \"' + this.options[this.selectedIndex].text.replace('Sort by: ', '') + '\" in a real application.');
            });
        }

        // App install buttons functionality
        const installBtns = document.querySelectorAll('.btn-app-install');
        installBtns.forEach(button => {
            button.addEventListener('click', function() {
                const appName = this.closest('.app-card').querySelector('.app-name').textContent;
                const appStatus = this.closest('.app-card').querySelector('.app-status').textContent.toLowerCase();

                if (appStatus === 'free') {
                    alert('This would install the ' + appName + ' app in a real application.');
                } else {
                    alert('This would start the purchase process for the ' + appName + ' app in a real application.');
                }
            });
        });

        // App details buttons functionality
        const detailsBtns = document.querySelectorAll('.btn-app-details');
        detailsBtns.forEach(button => {
            button.addEventListener('click', function() {
                const appName = this.closest('.app-card').querySelector('.app-name').textContent;
                alert('This would show details for the ' + appName + ' app in a real application.');
            });
        });

        // Claim offer buttons functionality
        const claimBtns = document.querySelectorAll('.btn-claim-offer');
        claimBtns.forEach(button => {
            button.addEventListener('click', function() {
                const offerTitle = this.closest('.offer-card').querySelector('.offer-title').textContent;
                alert('This would claim the \"' + offerTitle + '\" offer in a real application.');
            });
        });

        // Category card functionality
        const categoryCards = document.querySelectorAll('.category-card');
        categoryCards.forEach(card => {
            card.addEventListener('click', function() {
                const categoryName = this.querySelector('.category-name').textContent;
                alert('This would show all apps in the ' + categoryName + ' category in a real application.');
            });
        });

        // Explore apps button functionality
        const exploreAppsBtn = document.querySelector('.btn-explore-apps');
        if (exploreAppsBtn) {
            exploreAppsBtn.addEventListener('click', function() {
                alert('This would take you to the app marketplace in a real application.');
            });
        }
    });
"; ?>
</script>

<?php require APPROOT . '/views/layouts/footer.php'; ?>