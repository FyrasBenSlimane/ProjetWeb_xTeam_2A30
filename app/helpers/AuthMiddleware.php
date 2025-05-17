<?php
/**
 * Authentication and Authorization Middleware
 * Handles role-based access control for routes
 */
class AuthMiddleware {
    /**
     * Check if user is logged in
     *
     * @return bool
     */
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    /**
     * Check if user has specific role
     *
     * @param string|array $roles Role or array of roles to check
     * @return bool
     */
    public static function hasRole($roles) {
        if (!self::isLoggedIn()) {
            return false;
        }
        
        if (!isset($_SESSION['user_account_type'])) {
            return false;
        }
        
        // If roles is a string, convert to array for consistency
        if (!is_array($roles)) {
            $roles = [$roles];
        }
        
        return in_array($_SESSION['user_account_type'], $roles);
    }
    
    /**
     * Redirect user to appropriate dashboard based on role
     *
     * @return void
     */
    public static function redirectToDashboard() {
        if (!self::isLoggedIn()) {
            redirect('users/auth');
            return;
        }
        
        switch ($_SESSION['user_account_type']) {
            case 'admin':
                redirect('dashboard');
                break;
            case 'freelancer':
                redirect('freelance');
                break;
            case 'client':
                redirect('client');
                break;
            default:
                // Default to landing page if role doesn't match
                redirect('');
                break;
        }
    }
    
    /**
     * Ensures a user is authenticated, or redirects to login
     *
     * @return void
     */
    public static function requireLogin() {
        if (!self::isLoggedIn()) {
            // Store intended URL to redirect back after login
            if (isset($_SERVER['REQUEST_URI'])) {
                $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
            }
            
            redirect('users/auth');
            exit;
        }
    }
    
    /**
     * Ensures a user has the required role, or redirects appropriately
     *
     * @param string|array $roles Role or array of roles allowed to access
     * @return void
     */
    public static function requireRole($roles) {
        self::requireLogin();
        
        if (!self::hasRole($roles)) {
            // If user is logged in but doesn't have the right role, 
            // redirect to their appropriate dashboard
            self::redirectToDashboard();
            exit;
        }
    }
} 