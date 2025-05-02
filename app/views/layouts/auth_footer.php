</main>
    
    <!-- Copyright Footer -->
    <footer class="auth-footer py-4">
        <div class="container text-center">
            <a href="<?php echo URL_ROOT; ?>" class="footer-brand text-decoration-none">
                <i class="fas fa-briefcase me-2"></i><?php echo SITE_NAME; ?>
            </a>
            <p class="mt-2 mb-0 small text-muted">
                &copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All Rights Reserved.
            </p>
        </div>
    </footer>
    
    <!-- Bootstrap JS with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- jQuery (needed for some components) -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    
    <!-- Custom JS -->
    <script src="<?php echo URL_ROOT; ?>/public/js/main.js"></script>
    
    <!-- Page-specific JS -->
    <?php if(isset($data['js'])) : ?>
        <?php foreach($data['js'] as $js) : ?>
            <script src="<?php echo URL_ROOT; ?>/public/js/<?php echo $js; ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>