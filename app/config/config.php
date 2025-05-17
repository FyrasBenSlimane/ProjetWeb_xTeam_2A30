<?php
// Prevent redefinition of constants
if (!defined('DB_HOST')) {
    // Database configuration
    define('DB_HOST', '135.125.129.11');  // Your VPS IP
    define('DB_USER', 'lensi_user');      // MySQL username
    define('DB_PASS', 'Str0ngAdm1nP@ssw0rd!');  // MySQL password - updated to match VPS
    define('DB_NAME', 'lensi');

    // URL Root
    define('URL_ROOT', 'http://localhost/web');
    define('SITE_NAME', 'lenSi');
    define('SITE_DESCRIPTION', 'Your creative services marketplace');

    // GitHub OAuth Credentials (Replace with your actual credentials)
    define('GITHUB_CLIENT_ID', 'Ov23liKyMtQiCKOI3aWU');
    define('GITHUB_CLIENT_SECRET', '5d0d8cb6ab2b56aca1782c368a9744390ed2cd4a');
    define('GITHUB_CALLBACK_URL', URL_ROOT . '/users/githubAuth');

    // Google OAuth Credentials
    define('GOOGLE_CLIENT_ID', '784347061118-rfdt59vvfdfeob11oo11f76ipgn0cspl.apps.googleusercontent.com');
    define('GOOGLE_CLIENT_SECRET', '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');
    define('GOOGLE_CALLBACK_URL', URL_ROOT . '/users/googleAuth');

    // App Root
    define('APP_ROOT', dirname(dirname(__FILE__)));
    
    // Public Path
    define('PUBLIC_PATH', dirname(dirname(dirname(__FILE__))) . '/public');

    // Session configuration
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    // Additional security settings
    ini_set('session.cookie_secure', 1); // Only send cookies over HTTPS
    ini_set('session.cookie_samesite', 'Lax'); // Prevent CSRF attacks
    ini_set('session.gc_maxlifetime', 3600); // Session timeout after 1 hour of inactivity
    
    // Only start session if it hasn't been started already
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}