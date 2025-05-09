<?php

/**
 * Admin Dashboard - Main Index File
 * This file serves as the base template for the admin dashboard
 * It includes the navbar and dynamically loads content based on user selection
 */

// Redirect if not admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_account_type']) || $_SESSION['user_account_type'] !== 'admin') {
    redirect('users/login');
}

// Get the active page (default to 'dashboard')
$activePage = isset($_GET['page']) ? $_GET['page'] : 'dashboard';

// List of valid pages
$validPages = ['dashboard', 'users', 'services', 'orders', 'settings'];

// Validate requested page
if (!in_array($activePage, $validPages)) {
    $activePage = 'dashboard'; // Default to dashboard if invalid
}

// Set content to be passed to layout
ob_start();
?>

<!-- MAIN -->
<main>
    <?php
    // Dynamically include the active page content
    $contentPath = APPROOT . '/views/dashboard/' . $activePage . '.php';
    if (file_exists($contentPath)) {
        include($contentPath);
    } else {
        echo '<div class="error-container">';
        echo '<i class="bx bx-error-circle"></i>';
        echo '<h2>Content Not Found</h2>';
        echo '<p>The requested page could not be found. Please select another option from the menu.</p>';
        echo '</div>';
    }
    ?>
</main>
<!-- MAIN -->

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Check for saved dark mode preference
        if (localStorage.getItem('darkMode') === 'true') {
            document.body.classList.add('dark');
        }

        // Notification Menu Toggle
        const notificationIcon = document.getElementById('notificationIcon');
        const notificationMenu = document.getElementById('notificationMenu');
        if (notificationIcon && notificationMenu) {
            notificationIcon.addEventListener('click', function(e) {
                e.preventDefault();
                notificationMenu.classList.toggle('show');
                if (profileMenu) profileMenu.classList.remove('show'); // Close profile menu if open
            });
        }

        // Profile Menu Toggle
        const profileIcon = document.getElementById('profileIcon');
        const profileMenu = document.getElementById('profileMenu');
        if (profileIcon && profileMenu) {
            profileIcon.addEventListener('click', function(e) {
                e.preventDefault();
                profileMenu.classList.toggle('show');
                if (notificationMenu) notificationMenu.classList.remove('show'); // Close notification menu if open
            });
        }

        // Close menus when clicking outside
        document.addEventListener('click', function(e) {
            if (notificationMenu && !e.target.closest('.notification') && !e.target.closest('.notification-menu')) {
                notificationMenu.classList.remove('show');
            }

            if (profileMenu && !e.target.closest('.profile') && !e.target.closest('.profile-menu')) {
                profileMenu.classList.remove('show');
            }
        });
    });
</script>

<?php
// Capture content to pass to layout
$content = ob_get_clean();

// Pass content to dashboard layout
require_once APPROOT . '/views/layouts/dashboard.php';
?>