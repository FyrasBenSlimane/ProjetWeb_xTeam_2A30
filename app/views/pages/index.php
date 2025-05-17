<?php require APPROOT . '/views/layouts/header.php'; ?>

<?php
// This file is now handled by the PagesController which routes to different pages
// based on user login status and role:
// - Non-logged in users: See the landing page
// - Freelancers: Redirected to pages/freelance
// - Clients: Currently shown the landing page (will have own dashboard in future)
// - Admins: Redirected to the admin dashboard
//
// See PagesController->index() for the complete routing logic.
?>

<?php require APPROOT . '/views/layouts/footer.php'; ?>