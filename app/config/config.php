<?php
// Prevent redefinition of constants
if (!defined('DB_HOST')) {
    // Database configuration
    define('DB_HOST', '54.234.217.13');  // Your VPS IP
    define('DB_USER', 'lensi_user');      // MySQL username
    define('DB_PASS', 'StrongPassword123!');  // MySQL password - updated to match VPS
    define('DB_NAME', 'lensi_db');

    // URL Root
    define('URL_ROOT', 'http://localhost/web');
    define('SITE_NAME', 'lenSi');
    define('SITE_DESCRIPTION', 'Your creative services marketplace');

    // GitHub OAuth Credentials (Replace with your actual credentials)
    define('GITHUB_CLIENT_ID', 'Ov23liKyMtQiCKOI3aWU');
    define('GITHUB_CLIENT_SECRET', '5d0d8cb6ab2b56aca1782c368a9744390ed2cd4a');
    define('GITHUB_CALLBACK_URL', URL_ROOT . '/users/githubAuth');

    // App Root
    define('APP_ROOT', dirname(dirname(__FILE__)));
    
    // Public Path
    define('PUBLIC_PATH', dirname(dirname(dirname(__FILE__))) . '/public');

    // Session configuration
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    
    // Only start session if it hasn't been started already
    // Start session if it hasn't been started already
if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}