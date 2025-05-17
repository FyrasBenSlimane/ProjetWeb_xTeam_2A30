<?php
// 404 Not Found Page
?>

<div class="not-found-container min-vh-100 d-flex flex-column justify-content-center align-items-center p-4">
    <div class="text-center">
        <h1 class="display-1 fw-bold text-primary mb-4">404</h1>
        <h2 class="mb-4">Page Not Found</h2>
        <p class="mb-5 text-muted">
            The page you are looking for might have been removed, had its name changed,
            or is temporarily unavailable.
        </p>
        <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
            <a href="<?php echo URL_ROOT; ?>" class="btn btn-primary px-4 py-2">
                <i class="fas fa-home me-2"></i>
                Back to Home
            </a>
            <a href="<?php echo URL_ROOT; ?>/contact" class="btn btn-outline-secondary px-4 py-2">
                <i class="fas fa-envelope me-2"></i>
                Contact Support
            </a>
        </div>
    </div>
    <div class="mt-5 pt-5">
        <div class="text-center">
            <p class="text-muted">
                <i class="fas fa-search me-2"></i>
                Looking for something specific?
            </p>
            <form action="<?php echo URL_ROOT; ?>/search" method="get" class="d-flex mx-auto mt-3" style="max-width: 500px;">
                <input type="text" name="q" class="form-control" placeholder="Search..." aria-label="Search">
                <button class="btn btn-outline-primary ms-2" type="submit">Search</button>
            </form>
        </div>
    </div>
</div>

<style>
    .not-found-container {
        padding-top: 2rem;
        padding-bottom: 4rem;
        background-color: #f8f9fa;
    }
    
    .not-found-container h1 {
        font-size: 8rem;
        letter-spacing: -0.05em;
    }
    
    .not-found-container h2 {
        font-size: 2rem;
        font-weight: 500;
    }
    
    @media (max-width: 576px) {
        .not-found-container h1 {
            font-size: 6rem;
        }
        
        .not-found-container h2 {
            font-size: 1.5rem;
        }
    }
</style> 