<?php
// Prevent redefinition of constants
if (!defined('DB_HOST')) {
    // Database configuration
    define('DB_HOST', 'localhost');
    define('DB_USER', 'root');
    define('DB_PASS', '');
    define('DB_NAME', 'lensi_db');

    // URL Root
    define('URL_ROOT', 'http://localhost/web');
    define('SITE_NAME', 'lenSi');
    define('SITE_DESCRIPTION', 'Your creative services marketplace');

    // App Root
    define('APP_ROOT', dirname(dirname(__FILE__)));

    // Session configuration
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);

    // Only start session if it hasn't been started already
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
}
