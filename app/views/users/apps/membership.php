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

    <section class="membership-section py-4">
        <div class="container">
            <!-- Current Plan -->
            <div class="row mb-4">
                <div class="col-12">
                    <div class="current-plan-card">
                        <div class="plan-header">
                            <div class="plan-info">
                                <div class="plan-badge">
                                    <i class="fas fa-medal"></i> Your Current Plan
                                </div>
                                <h3 class="plan-name">Basic (Free)</h3>
                                <p class="plan-description">Get started as a freelancer with basic features</p>
                            </div>
                            <div class="plan-actions">
                                <button class="btn-upgrade-plan">
                                    <i class="fas fa-arrow-circle-up me-2"></i> Upgrade Plan
                                </button>
                            </div>
                        </div>
                        <div class="plan-features">
                            <div class="feature-item">
                                <i class="fas fa-check feature-included"></i>
                                <span>10 job applications per month</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check feature-included"></i>
                                <span>Create up to 5 services</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-check feature-included"></i>
                                <span>Basic profile visibility</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-times feature-not-included"></i>
                                <span>Featured profile placement</span>
                            </div>
                            <div class="feature-item">
                                <i class="fas fa-times feature-not-included"></i>
                                <span>Priority customer support</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Available Plans -->
            <div class="row mb-4">
                <div class="col-12">
                    <h3 class="section-title">Available Plans</h3>
                    <p class="section-description">Choose the plan that best fits your freelancing needs</p>
                </div>
            </div>

            <div class="row plan-cards-container g-4">
                <!-- Basic Plan -->
                <div class="col-md-4">
                    <div class="plan-card plan-current">
                        <div class="plan-card-header">
                            <h4>Basic</h4>
                            <div class="plan-price">
                                <span class="price">Free</span>
                            </div>
                            <p class="plan-subtitle">For beginners just getting started</p>
                        </div>
                        <div class="plan-card-body">
                            <ul class="plan-features-list">
                                <li><i class="fas fa-check"></i> 10 job applications per month</li>
                                <li><i class="fas fa-check"></i> Create up to 5 services</li>
                                <li><i class="fas fa-check"></i> Basic profile visibility</li>
                                <li><i class="fas fa-check"></i> Standard commission rates</li>
                                <li><i class="fas fa-check"></i> Community forum access</li>
                                <li class="feature-disabled"><i class="fas fa-times"></i> Featured profile placement</li>
                                <li class="feature-disabled"><i class="fas fa-times"></i> Priority customer support</li>
                                <li class="feature-disabled"><i class="fas fa-times"></i> Custom profile URL</li>
                            </ul>
                        </div>
                        <div class="plan-card-footer">
                            <div class="current-plan-badge">
                                Current Plan
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Plus Plan -->
                <div class="col-md-4">
                    <div class="plan-card">
                        <div class="plan-card-header">
                            <h4>Plus</h4>
                            <div class="plan-price">
                                <span class="currency">$</span>
                                <span class="price">9.99</span>
                                <span class="period">/month</span>
                            </div>
                            <p class="plan-subtitle">For serious freelancers</p>
                        </div>
                        <div class="plan-card-body">
                            <ul class="plan-features-list">
                                <li><i class="fas fa-check"></i> 50 job applications per month</li>
                                <li><i class="fas fa-check"></i> Create up to 15 services</li>
                                <li><i class="fas fa-check"></i> Enhanced profile visibility</li>
                                <li><i class="fas fa-check"></i> Reduced commission rates</li>
                                <li><i class="fas fa-check"></i> Community forum access</li>
                                <li><i class="fas fa-check"></i> Featured profile placement</li>
                                <li><i class="fas fa-check"></i> Priority customer support</li>
                                <li class="feature-disabled"><i class="fas fa-times"></i> Custom profile URL</li>
                            </ul>
                        </div>
                        <div class="plan-card-footer">
                            <button class="btn-select-plan">Select Plan</button>
                        </div>
                    </div>
                </div>

                <!-- Pro Plan -->
                <div class="col-md-4">
                    <div class="plan-card plan-recommended">
                        <div class="recommended-badge">Recommended</div>
                        <div class="plan-card-header">
                            <h4>Professional</h4>
                            <div class="plan-price">
                                <span class="currency">$</span>
                                <span class="price">19.99</span>
                                <span class="period">/month</span>
                            </div>
                            <p class="plan-subtitle">For power freelancers</p>
                        </div>
                        <div class="plan-card-body">
                            <ul class="plan-features-list">
                                <li><i class="fas fa-check"></i> Unlimited job applications</li>
                                <li><i class="fas fa-check"></i> Create unlimited services</li>
                                <li><i class="fas fa-check"></i> Maximum profile visibility</li>
                                <li><i class="fas fa-check"></i> Lowest commission rates</li>
                                <li><i class="fas fa-check"></i> Community forum access</li>
                                <li><i class="fas fa-check"></i> Featured profile placement</li>
                                <li><i class="fas fa-check"></i> Priority customer support</li>
                                <li><i class="fas fa-check"></i> Custom profile URL</li>
                            </ul>
                        </div>
                        <div class="plan-card-footer">
                            <button class="btn-select-plan">Select Plan</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Membership FAQ -->
            <div class="row mt-5">
                <div class="col-12 mb-4">
                    <h3 class="section-title">Frequently Asked Questions</h3>
                </div>

                <div class="col-lg-6">
                    <div class="faq-item">
                        <h5 class="faq-question">How do I upgrade my plan?</h5>
                        <p class="faq-answer">Simply select the plan you'd like to upgrade to and follow the payment instructions. Your new plan benefits will be activated immediately after successful payment.</p>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="faq-item">
                        <h5 class="faq-question">Can I downgrade my plan?</h5>
                        <p class="faq-answer">Yes, you can downgrade your plan at any time. The changes will take effect at the end of your current billing cycle.</p>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="faq-item">
                        <h5 class="faq-question">Are there any hidden fees?</h5>
                        <p class="faq-answer">No, the price you see is the price you pay. There are no hidden fees or additional charges beyond the listed subscription price.</p>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="faq-item">
                        <h5 class="faq-question">What payment methods do you accept?</h5>
                        <p class="faq-answer">We accept all major credit cards, PayPal, and select regional payment methods. Detailed payment options will be shown during checkout.</p>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="faq-item">
                        <h5 class="faq-question">Will my plan auto-renew?</h5>
                        <p class="faq-answer">Yes, all paid plans are set to auto-renew by default to ensure uninterrupted service. You can disable auto-renewal from your account settings.</p>
                    </div>
                </div>

                <div class="col-lg-6">
                    <div class="faq-item">
                        <h5 class="faq-question">What if I'm not satisfied with my plan?</h5>
                        <p class="faq-answer">We offer a 14-day money-back guarantee for all paid plans. If you're not satisfied, contact our support team within 14 days of purchase for a full refund.</p>
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

    /* Current Plan Card */
    .current-plan-card {
        background-color: var(--white);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-sm);
        overflow: hidden;
    }

    .plan-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 1.5rem;
        border-bottom: 1px solid var(--gray-200);
        background: linear-gradient(to right, rgba(20, 168, 0, 0.05), rgba(20, 168, 0, 0.01));
    }

    .plan-badge {
        display: inline-block;
        background-color: rgba(20, 168, 0, 0.1);
        color: #14a800;
        padding: 0.25rem 0.75rem;
        border-radius: var(--border-radius-md);
        font-size: 0.85rem;
        font-weight: var(--font-weight-medium);
        margin-bottom: 0.5rem;
    }

    .plan-name {
        font-size: 1.5rem;
        font-weight: var(--font-weight-bold);
        color: var(--secondary);
        margin-bottom: 0.25rem;
    }

    .plan-description {
        font-size: 0.95rem;
        color: var(--gray-600);
        margin-bottom: 0;
    }

    .plan-actions {
        display: flex;
        align-items: center;
    }

    .btn-upgrade-plan {
        background-color: #14a800;
        color: var(--white);
        border: none;
        border-radius: var(--border-radius-md);
        padding: 0.75rem 1.25rem;
        font-size: 0.95rem;
        font-weight: var(--font-weight-medium);
        cursor: pointer;
        transition: all var(--transition-normal);
        display: inline-flex;
        align-items: center;
    }

    .btn-upgrade-plan:hover {
        background-color: #0e7400;
        transform: translateY(-2px);
        box-shadow: var(--shadow-md);
    }

    .plan-features {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1rem;
        padding: 1.5rem;
    }

    .feature-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.95rem;
        color: var(--gray-700);
    }

    .feature-included {
        color: #14a800;
    }

    .feature-not-included {
        color: var(--gray-500);
    }

    /* Section titles */
    .section-title {
        font-size: 1.5rem;
        font-weight: var(--font-weight-bold);
        color: var(--secondary);
        margin-bottom: 0.5rem;
    }

    .section-description {
        font-size: 1rem;
        color: var(--gray-600);
        margin-bottom: 1.5rem;
    }

    /* Plan Cards */
    .plan-cards-container {
        margin-bottom: 2rem;
    }

    .plan-card {
        background-color: var(--white);
        border-radius: var(--border-radius-lg);
        box-shadow: var(--shadow-sm);
        height: 100%;
        transition: all var(--transition-normal);
        position: relative;
        display: flex;
        flex-direction: column;
        overflow: hidden;
    }

    .plan-card:hover {
        transform: translateY(-5px);
        box-shadow: var(--shadow-md);
    }

    .plan-current {
        border: 2px solid #14a800;
    }

    .plan-recommended {
        border: 2px solid #9b59b6;
    }

    .recommended-badge {
        position: absolute;
        top: 0;
        right: 0;
        background: linear-gradient(135deg, #9b59b6, #8e44ad);
        color: white;
        padding: 0.4rem 1.25rem;
        font-size: 0.8rem;
        font-weight: var(--font-weight-medium);
        transform: translateX(30%) translateY(0%) rotate(45deg);
        transform-origin: top left;
        width: 140px;
        text-align: center;
    }

    .plan-card-header {
        padding: 1.5rem;
        border-bottom: 1px solid var(--gray-200);
        text-align: center;
    }

    .plan-card-header h4 {
        font-size: 1.25rem;
        font-weight: var(--font-weight-bold);
        color: var(--secondary);
        margin-bottom: 0.75rem;
    }

    .plan-price {
        margin-bottom: 0.75rem;
    }

    .currency {
        font-size: 1.25rem;
        font-weight: var(--font-weight-semibold);
        vertical-align: super;
    }

    .price {
        font-size: 2.5rem;
        font-weight: var(--font-weight-bold);
        color: var(--secondary);
    }

    .period {
        font-size: 0.95rem;
        color: var(--gray-600);
    }

    .plan-subtitle {
        font-size: 0.9rem;
        color: var(--gray-600);
        margin-bottom: 0;
    }

    .plan-card-body {
        padding: 1.5rem;
        flex-grow: 1;
    }

    .plan-features-list {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .plan-features-list li {
        display: flex;
        align-items: center;
        gap: 0.75rem;
        font-size: 0.9rem;
        color: var(--secondary);
        margin-bottom: 0.75rem;
    }

    .plan-features-list li i {
        color: #14a800;
        font-size: 0.85rem;
        width: 16px;
    }

    .plan-features-list li.feature-disabled {
        color: var(--gray-500);
    }

    .plan-features-list li.feature-disabled i {
        color: var(--gray-500);
    }

    .plan-card-footer {
        padding: 1.5rem;
        border-top: 1px solid var(--gray-200);
        text-align: center;
    }

    .btn-select-plan {
        background-color: #14a800;
        color: var(--white);
        border: none;
        border-radius: var(--border-radius-md);
        padding: 0.75rem 1rem;
        width: 100%;
        font-size: 0.95rem;
        font-weight: var(--font-weight-medium);
        cursor: pointer;
        transition: all var(--transition-normal);
    }

    .btn-select-plan:hover {
        background-color: #0e7400;
        transform: translateY(-2px);
    }

    .current-plan-badge {
        display: inline-block;
        background-color: rgba(20, 168, 0, 0.1);
        color: #14a800;
        padding: 0.5rem 1rem;
        border-radius: var(--border-radius-md);
        font-size: 0.9rem;
        font-weight: var(--font-weight-medium);
    }

    /* FAQ Section */
    .faq-item {
        margin-bottom: 1.75rem;
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
        .plan-header {
            flex-direction: column;
            text-align: center;
        }

        .plan-actions {
            margin-top: 1rem;
        }

        .plan-features {
            grid-template-columns: 1fr;
        }
    }

    @media (max-width: 768px) {
        .page-title {
            font-size: 1.5rem;
        }

        .plan-cards-container {
            display: flex;
            flex-direction: column;
        }

        .plan-card {
            margin-bottom: 1.5rem;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Upgrade plan button functionality
        const upgradeBtn = document.querySelector('.btn-upgrade-plan');
        if (upgradeBtn) {
            upgradeBtn.addEventListener('click', function() {
                alert('This is a demo button. In a real application, this would show plan upgrade options.');
            });
        }

        // Select plan buttons functionality
        const selectPlanBtns = document.querySelectorAll('.btn-select-plan');
        selectPlanBtns.forEach(button => {
            button.addEventListener('click', function() {
                const planName = this.closest('.plan-card').querySelector('h4').textContent;
                alert('This is a demo button. In a real application, this would start the process to purchase the ' + planName + ' plan.');
            });
        });
    });
</script>

<?php require APPROOT . '/views/layouts/footer.php'; ?>