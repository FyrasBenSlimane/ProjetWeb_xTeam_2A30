<style>
    .error-container {
        margin: 5rem auto;
        font-family: 'Segoe UI', Roboto, 'Helvetica Neue', Arial, sans-serif;
    }
    .error-card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.12);
        overflow: hidden;
    }
    .error-header {
        background: linear-gradient(120deg, #2c3e50, #1a252f);
        color: white;
        padding: 1.75rem;
        border-bottom: none;
    }
    .error-body {
        padding: 3rem 2.5rem;
    }
    .error-icon {
        color: #e74c3c;
        margin-bottom: 1.75rem;
        filter: drop-shadow(0 4px 6px rgba(231, 76, 60, 0.2));
        transition: transform 0.3s ease;
    }
    .error-icon:hover {
        transform: scale(1.05);
    }
    .error-title {
        font-weight: 600;
        margin-bottom: 1.25rem;
        color: #2c3e50;
        font-size: 1.75rem;
    }
    .error-message {
        color: #5a6a7e;
        font-size: 1.1rem;
        line-height: 1.6;
        margin-bottom: 2.5rem;
    }
    .technical-details {
        background-color: #f8fafc;
        border-left: 4px solid #3498db;
        padding: 1.5rem;
        margin-bottom: 2.5rem;
        border-radius: 6px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.05);
    }
    .technical-details h5 {
        color: #2c3e50;
        margin-bottom: 1rem;
        font-weight: 600;
    }
    .technical-details code {
        display: block;
        padding: 1.25rem;
        background: #f1f5f9;
        border-radius: 6px;
        color: #e74c3c;
        font-size: 0.9rem;
        overflow-x: auto;
        border: 1px solid rgba(0,0,0,0.05);
    }
    .home-button {
        background-color: #3498db;
        border: none;
        padding: 0.875rem 2.5rem;
        font-weight: 500;
        border-radius: 6px;
        transition: all 0.3s ease;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        font-size: 0.95rem;
    }
    .home-button:hover {
        background-color: #2980b9;
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(52, 152, 219, 0.25);
    }
    .error-footer {
        background-color: #f8fafc;
        border-top: 1px solid #eef2f7;
        padding: 1.25rem;
        color: #64748b;
    }
</style>

<div class="container error-container">
    <div class="row">
        <div class="col-lg-8 col-md-10 mx-auto">
            <div class="card error-card">
                <div class="card-header error-header">
                    <h2 class="mb-0">Error Encountered</h2>
                </div>
                <div class="card-body error-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-exclamation-circle fa-4x error-icon"></i>
                        <h3 class="error-title">We apologize for the inconvenience</h3>
                        <p class="error-message">The system encountered an unexpected condition that prevented it from fulfilling the request. Our team has been notified of this issue.</p>
                    </div>
                    
                    <?php if (isset($e) && ENVIRONMENT === 'development'): ?>
                        <div class="technical-details">
                            <h5><i class="fas fa-terminal mr-2"></i> Technical Details</h5>
                            <code><?php echo htmlspecialchars($e->getMessage()); ?></code>
                        </div>
                    <?php endif; ?>
                    
                    <div class="text-center mt-4">
                        <a href="<?php echo URLROOT; ?>" class="btn btn-primary home-button">
                            <i class="fas fa-home mr-2"></i> Return to Homepage
                        </a>
                    </div>
                </div>
                <div class="card-footer error-footer text-center">
                    <small>If this problem persists, please contact <a href="mailto:support@example.com" class="text-primary">technical support</a></small>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APP_PATH . '/views/layouts/footer.php'; ?>