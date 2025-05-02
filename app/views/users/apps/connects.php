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
                <div class="col-md-8">
                    <h1 class="page-title"><?php echo $data['title']; ?></h1>
                    <p class="page-description"><?php echo $data['description']; ?></p>
                </div>
                <div class="col-md-4 d-flex align-items-center justify-content-end">
                    <button class="btn-buy-connects">
                        <i class="fas fa-plus-circle me-2"></i> Buy Connects
                    </button>
                </div>
            </div>
        </div>
    </section>

    <section class="connects-section py-4">
        <div class="container">
            <!-- Connects Summary -->
            <div class="row mb-4">
                <div class="col-lg-8 mb-4">
                    <div class="connects-summary">
                        <div class="summary-header">
                            <h3>Your Connects</h3>
                        </div>
                        <div class="summary-content">
                            <div class="summary-item">
                                <div class="connects-count">
                                    <span class="count"><?php echo $data['connects_available']; ?></span>
                                    <span class="label">Available</span>
                                </div>
                                <div class="connects-visual">
                                    <div class="visual-bar">
                                        <div class="visual-fill" style="width: 70%;"></div>
                                    </div>
                                </div>
                            </div>
                            <div class="summary-item">
                                <div class="connects-stats">
                                    <div class="stats-item">
                                        <div class="stats-icon gained">
                                            <i class="fas fa-plus-circle"></i>
                                        </div>
                                        <div class="stats-info">
                                            <span class="count">10</span>
                                            <span class="label">Gained this month</span>
                                        </div>
                                    </div>
                                    <div class="stats-item">
                                        <div class="stats-icon spent">
                                            <i class="fas fa-minus-circle"></i>
                                        </div>
                                        <div class="stats-info">
                                            <span class="count"><?php echo $data['connects_spent']; ?></span>
                                            <span class="label">Spent this month</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="summary-footer">
                            <div class="usage-tip">
                                <i class="fas fa-lightbulb tip-icon"></i>
                                <p class="tip-text">Most jobs cost between 2-6 connects to apply. Your connects will refill with 10 free connects on the 1st of each month.</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 mb-4">
                    <div class="connects-card">
                        <div class="card-header">
                            <h5>Connects Packages</h5>
                        </div>
                        <div class="card-body">
                            <div class="package-options">
                                <div class="package-option">
                                    <div class="package-info">
                                        <span class="package-count">10</span>
                                        <span class="package-price">$1.50</span>
                                    </div>
                                    <button class="btn-select-package">Select</button>
                                </div>
                                <div class="package-option best-value">
                                    <div class="best-value-tag">Best Value</div>
                                    <div class="package-info">
                                        <span class="package-count">50</span>
                                        <span class="package-price">$7.00</span>
                                    </div>
                                    <button class="btn-select-package">Select</button>
                                </div>
                                <div class="package-option">
                                    <div class="package-info">
                                        <span class="package-count">20</span>
                                        <span class="package-price">$3.00</span>
                                    </div>
                                    <button class="btn-select-package">Select</button>
                                </div>
                                <div class="package-option">
                                    <div class="package-info">
                                        <span class="package-count">80</span>
                                        <span class="package-price">$11.00</span>
                                    </div>
                                    <button class="btn-select-package">Select</button>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="#" class="card-link">View all packages</a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Connects History -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="content-card">
                        <div class="card-header">
                            <h5>Connects History</h5>
                            <div class="header-actions">
                                <div class="date-filter">
                                    <button class="btn-date-filter">
                                        <i class="fas fa-calendar-alt me-2"></i>
                                        Last 30 Days
                                        <i class="fas fa-chevron-down ms-2"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="history-table-container">
                                <table class="history-table">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Description</th>
                                            <th>Type</th>
                                            <th class="text-end">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Apr 25, 2025</td>
                                            <td>Applied to job: Website Redesign Project</td>
                                            <td><span class="transaction-type spent">Spent</span></td>
                                            <td class="text-end">-4</td>
                                        </tr>
                                        <tr>
                                            <td>Apr 20, 2025</td>
                                            <td>Applied to job: E-commerce Development</td>
                                            <td><span class="transaction-type spent">Spent</span></td>
                                            <td class="text-end">-6</td>
                                        </tr>
                                        <tr>
                                            <td>Apr 15, 2025</td>
                                            <td>Applied to job: WordPress Blog Customization</td>
                                            <td><span class="transaction-type spent">Spent</span></td>
                                            <td class="text-end">-2</td>
                                        </tr>
                                        <tr>
                                            <td>Apr 10, 2025</td>
                                            <td>Applied to job: Logo Design for Tech Startup</td>
                                            <td><span class="transaction-type spent">Spent</span></td>
                                            <td class="text-end">-4</td>
                                        </tr>
                                        <tr>
                                            <td>Apr 5, 2025</td>
                                            <td>Applied to job: Social Media Banner Design</td>
                                            <td><span class="transaction-type spent">Spent</span></td>
                                            <td class="text-end">-2</td>
                                        </tr>
                                        <tr>
                                            <td>Apr 1, 2025</td>
                                            <td>Monthly free connects</td>
                                            <td><span class="transaction-type gained">Gained</span></td>
                                            <td class="text-end">+10</td>
                                        </tr>
                                        <tr>
                                            <td>Mar 28, 2025</td>
                                            <td>Applied to job: Mobile App UI Design</td>
                                            <td><span class="transaction-type spent">Spent</span></td>
                                            <td class="text-end">-2</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="card-footer">
                            <div class="pagination-container">
                                <button class="btn-pagination btn-prev" disabled>
                                    <i class="fas fa-chevron-left"></i> Previous
                                </button>
                                <div class="pagination-info">Page 1 of 1</div>
                                <button class="btn-pagination btn-next" disabled>
                                    Next <i class="fas fa-chevron-right"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FAQ Section -->
            <div class="row mt-5">
                <div class="col-12 mb-4">
                    <h3 class="section-title">About Connects</h3>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="faq-item">
                        <h5 class="faq-question">What are Connects?</h5>
                        <p class="faq-answer">Connects are tokens that you use to submit proposals for jobs on our platform. Each job application costs a specific number of Connects, typically between 2 and 6, depending on the job's budget and other factors.</p>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="faq-item">
                        <h5 class="faq-question">How do I get more Connects?</h5>
                        <p class="faq-answer">You receive 10 free Connects each month. You can purchase additional Connects in various packages starting at $1.50 for 10 Connects. Some membership plans also include extra Connects as a benefit.</p>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="faq-item">
                        <h5 class="faq-question">Do Connects expire?</h5>
                        <p class="faq-answer">No, your Connects do not expire as long as your account remains active. If your account becomes inactive for an extended period, your Connects may be reset.</p>
                    </div>
                </div>

                <div class="col-lg-6 mb-4">
                    <div class="faq-item">
                        <h5 class="faq-question">Can I get a refund for unused Connects?</h5>
                        <p class="faq-answer">Connects purchases are non-refundable. However, in some cases, you might receive Connects back if a job posting is removed before you receive a response to your proposal.</p>
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

    .btn-buy-connects {
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

    .btn-buy-connects:hover {
        background-color: #0e7400;
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    /* Connects Summary */
    .connects-summary {
        background-color: var(--white);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .summary-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--gray-200);
    }

    .summary-header h3 {
        font-size: 1.25rem;
        font-weight: var(--font-weight-semibold);
        color: var(--secondary);
        margin: 0;
    }

    .summary-content {
        padding: 1.5rem;
    }

    .summary-item {
        margin-bottom: 1.5rem;
    }

    .summary-item:last-child {
        margin-bottom: 0;
    }

    .connects-count {
        display: flex;
        align-items: baseline;
        margin-bottom: 1rem;
    }

    .connects-count .count {
        font-size: 2.5rem;
        font-weight: var(--font-weight-bold);
        color: #14a800;
        margin-right: 0.5rem;
    }

    .connects-count .label {
        font-size: 1.1rem;
        color: var(--secondary);
        font-weight: var(--font-weight-medium);
    }

    .connects-visual {
        width: 100%;
    }

    .visual-bar {
        width: 100%;
        height: 8px;
        background-color: var(--gray-200);
        border-radius: var(--border-radius-lg);
        overflow: hidden;
    }

    .visual-fill {
        height: 100%;
        background: linear-gradient(to right, #14a800, #26d917);
        border-radius: var(--border-radius-lg);
    }

    .connects-stats {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
    }

    .stats-item {
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .stats-icon {
        width: 40px;
        height: 40px;
        border-radius: var(--border-radius-circle);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.1rem;
        color: var(--white);
    }

    .stats-icon.gained {
        background-color: rgba(20, 168, 0, 0.1);
        color: #14a800;
    }

    .stats-icon.spent {
        background-color: rgba(231, 76, 60, 0.1);
        color: #e74c3c;
    }

    .stats-info {
        display: flex;
        flex-direction: column;
    }

    .stats-info .count {
        font-size: 1.25rem;
        font-weight: var(--font-weight-semibold);
        color: var(--secondary);
    }

    .stats-info .label {
        font-size: 0.85rem;
        color: var(--gray-600);
    }

    .summary-footer {
        padding: 1.25rem 1.5rem;
        border-top: 1px solid var(--gray-200);
        background-color: var(--gray-100);
    }

    .usage-tip {
        display: flex;
        align-items: flex-start;
        gap: 1rem;
    }

    .tip-icon {
        color: #f39c12;
        font-size: 1.25rem;
    }

    .tip-text {
        font-size: 0.9rem;
        color: var(--gray-700);
        margin: 0;
        line-height: 1.5;
    }

    /* Connects Card */
    .connects-card {
        background-color: var(--white);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-sm);
        height: 100%;
    }

    .connects-card .card-header {
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--gray-200);
    }

    .connects-card .card-header h5 {
        font-size: 1.1rem;
        font-weight: var(--font-weight-semibold);
        color: var(--secondary);
        margin: 0;
    }

    .connects-card .card-body {
        padding: 1.5rem;
    }

    .package-options {
        display: grid;
        grid-template-columns: repeat(2, 1fr);
        gap: 1rem;
    }

    .package-option {
        background-color: var(--gray-100);
        border-radius: var(--border-radius-md);
        padding: 1rem;
        text-align: center;
        position: relative;
        transition: all var(--transition-normal);
    }

    .package-option:hover {
        transform: translateY(-3px);
        box-shadow: var(--shadow-sm);
    }

    .package-option.best-value {
        border: 2px solid #14a800;
        background-color: rgba(20, 168, 0, 0.05);
    }

    .best-value-tag {
        position: absolute;
        top: -10px;
        left: 50%;
        transform: translateX(-50%);
        background-color: #14a800;
        color: white;
        font-size: 0.7rem;
        font-weight: var(--font-weight-medium);
        padding: 0.25rem 0.75rem;
        border-radius: var(--border-radius-sm);
    }

    .package-info {
        margin-bottom: 1rem;
    }

    .package-count {
        display: block;
        font-size: 1.75rem;
        font-weight: var(--font-weight-bold);
        color: var(--secondary);
        margin-bottom: 0.25rem;
    }

    .package-price {
        display: block;
        font-size: 0.95rem;
        color: var(--gray-600);
    }

    .btn-select-package {
        background-color: transparent;
        border: 1px solid #14a800;
        color: #14a800;
        border-radius: var(--border-radius-md);
        padding: 0.5rem;
        font-size: 0.85rem;
        font-weight: var(--font-weight-medium);
        cursor: pointer;
        transition: all var(--transition-normal);
        width: 100%;
    }

    .btn-select-package:hover {
        background-color: #14a800;
        color: white;
    }

    .connects-card .card-footer {
        padding: 1.25rem 1.5rem;
        border-top: 1px solid var(--gray-200);
        text-align: center;
    }

    .card-link {
        color: #14a800;
        text-decoration: none;
        font-size: 0.95rem;
        font-weight: var(--font-weight-medium);
        transition: all var(--transition-fast);
    }

    .card-link:hover {
        text-decoration: underline;
        color: #0e7400;
    }

    /* Content Card */
    .content-card {
        background-color: var(--white);
        border-radius: var(--border-radius-lg);
        overflow: hidden;
        box-shadow: var(--shadow-sm);
    }

    .content-card .card-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid var(--gray-200);
    }

    .content-card .card-header h5 {
        font-size: 1.1rem;
        font-weight: var(--font-weight-semibold);
        color: var(--secondary);
        margin: 0;
    }

    .header-actions {
        display: flex;
        gap: 1rem;
    }

    .btn-date-filter {
        background-color: transparent;
        border: 1px solid var(--gray-300);
        border-radius: var(--border-radius-md);
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        color: var(--secondary);
        cursor: pointer;
        transition: all var(--transition-normal);
        display: flex;
        align-items: center;
    }

    .btn-date-filter:hover {
        background-color: var(--gray-100);
        border-color: var(--gray-400);
    }

    /* History Table */
    .history-table-container {
        overflow-x: auto;
    }

    .history-table {
        width: 100%;
        border-collapse: collapse;
    }

    .history-table th,
    .history-table td {
        padding: 1rem 1.5rem;
        text-align: left;
        font-size: 0.95rem;
        border-bottom: 1px solid var(--gray-200);
    }

    .history-table th {
        font-weight: var(--font-weight-semibold);
        color: var(--secondary);
        background-color: var(--gray-100);
    }

    .history-table td {
        color: var(--gray-700);
    }

    .transaction-type {
        display: inline-block;
        padding: 0.25rem 0.5rem;
        border-radius: var(--border-radius-sm);
        font-size: 0.8rem;
        font-weight: var(--font-weight-medium);
    }

    .transaction-type.spent {
        background-color: rgba(231, 76, 60, 0.1);
        color: #e74c3c;
    }

    .transaction-type.gained {
        background-color: rgba(20, 168, 0, 0.1);
        color: #14a800;
    }

    /* Card Footer with Pagination */
    .content-card .card-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid var(--gray-200);
    }

    .pagination-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .btn-pagination {
        background-color: transparent;
        border: 1px solid var(--gray-300);
        border-radius: var(--border-radius-md);
        padding: 0.5rem 1rem;
        font-size: 0.85rem;
        color: var(--secondary);
        cursor: pointer;
        transition: all var(--transition-normal);
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .btn-pagination:not(:disabled):hover {
        background-color: var(--gray-100);
    }

    .btn-pagination:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .pagination-info {
        font-size: 0.9rem;
        color: var(--gray-600);
    }

    /* FAQ Section */
    .section-title {
        font-size: 1.5rem;
        font-weight: var(--font-weight-bold);
        color: var(--secondary);
        margin-bottom: 1.5rem;
    }

    .faq-item {
        margin-bottom: 1.5rem;
    }

    .faq-question {
        font-size: 1.1rem;
        font-weight: var(--font-weight-semibold);
        color: var(--secondary);
        margin-bottom: 0.75rem;
    }

    .faq-answer {
        font-size: 0.95rem;
        color: var(--gray-700);
        line-height: 1.6;
        margin: 0;
    }

    /* Responsive Adjustments */
    @media (max-width: 992px) {
        .connects-stats {
            flex-direction: column;
            gap: 1rem;
        }

        .package-options {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .page-title {
            font-size: 1.5rem;
        }

        .btn-buy-connects {
            margin-top: 1rem;
            width: 100%;
            justify-content: center;
        }

        .history-table th,
        .history-table td {
            padding: 0.75rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Buy connects button functionality
        const buyConnectsBtn = document.querySelector('.btn-buy-connects');
        if (buyConnectsBtn) {
            buyConnectsBtn.addEventListener('click', function() {
                alert('This button would open the connects purchase dialog in a real application.');
            });
        }

        // Package select buttons functionality
        const selectPackageBtns = document.querySelectorAll('.btn-select-package');
        selectPackageBtns.forEach(button => {
            button.addEventListener('click', function() {
                const packageCount = this.parentElement.previousElementSibling.querySelector('.package-count').textContent;
                const packagePrice = this.parentElement.previousElementSibling.querySelector('.package-price').textContent;

                alert('This would start the purchase process for ' + packageCount + ' connects at ' + packagePrice + ' in a real application.');
            });
        });

        // Date filter button functionality
        const dateFilterBtn = document.querySelector('.btn-date-filter');
        if (dateFilterBtn) {
            dateFilterBtn.addEventListener('click', function() {
                alert('This would open a date range picker in a real application.');
            });
        }

        // Pagination buttons functionality
        const paginationBtns = document.querySelectorAll('.btn-pagination');
        paginationBtns.forEach(button => {
            if (!button.disabled) {
                button.addEventListener('click', function() {
                    alert('This would navigate to the next/previous page of history in a real application.');
                });
            }
        });
    });
</script>

<?php require APPROOT . '/views/layouts/footer.php'; ?>