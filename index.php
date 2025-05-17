<?php
// Hide deprecation notices
error_reporting(E_ALL & ~E_DEPRECATED);

/**
 * Main entry point and router for lenSi platform
 */

define('ROOT_PATH', dirname(__FILE__));
define('APP_PATH', ROOT_PATH . '/app');

// Define constants used in views
define('APPROOT', APP_PATH);
define('URLROOT', '/web'); // Define base URL

// Load configuration and dependencies
require_once APP_PATH . '/config/config.php';
require_once APP_PATH . '/helpers/functions.php';  // Add helper functions
require_once APP_PATH . '/helpers/FileUpload.php'; // Add FileUpload helper
require_once APP_PATH . '/helpers/CurrentUser.php'; // Current user helper
require_once APP_PATH . '/core/Application.php';
require_once APP_PATH . '/core/Controller.php';
require_once APP_PATH . '/core/Database.php';

// Load models
require_once APP_PATH . '/models/User.php';

// Load all controllers (this ensures all controller classes are available)
foreach (glob(APP_PATH . '/controllers/*.php') as $controller) {
    require_once $controller;
}

// Initialize current user data if user is logged in
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
CurrentUser::initialize();

$app = new Application();
$app->run();