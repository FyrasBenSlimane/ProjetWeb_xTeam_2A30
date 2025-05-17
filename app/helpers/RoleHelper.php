<?php
/**
 * Role Helper Class
 * Provides utility functions for managing role-based UI and access
 */
class RoleHelper {
    /**
     * Get the layout template to use based on user role
     * 
     * @return string Layout template path
     */
    public static function getLayoutTemplate() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_account_type'])) {
            return 'layouts/main'; // Default layout for guests
        }
        
        switch ($_SESSION['user_account_type']) {
            case 'admin':
                return 'dashboard/dashboard_layout';
            case 'freelancer':
                return 'layouts/freelancer';
            case 'client':
                return 'layouts/client';
            default:
                return 'layouts/main';
        }
    }
    
    /**
     * Display UI element based on user roles
     * 
     * @param array|string $roles Role or roles that can see this element
     * @param string $content The HTML content to show
     * @return void
     */
    public static function showForRoles($roles, $content) {
        if (!is_array($roles)) {
            $roles = [$roles];
        }
        
        // Show for guests (not logged in)
        if (in_array('guest', $roles) && !isset($_SESSION['user_id'])) {
            echo $content;
            return;
        }
        
        // Show for authenticated users of specific roles
        if (isset($_SESSION['user_id']) && isset($_SESSION['user_account_type'])) {
            if (in_array($_SESSION['user_account_type'], $roles) || in_array('all', $roles)) {
                echo $content;
            }
        }
    }
    
    /**
     * Generate navigation menu based on user role
     * 
     * @return array Menu items
     */
    public static function getNavMenu() {
        $menu = [];
        
        // Menu items for guests (not logged in)
        if (!isset($_SESSION['user_id'])) {
            $menu = [
                ['title' => 'Home', 'url' => URLROOT . '/', 'icon' => 'home'],
                ['title' => 'Login', 'url' => URLROOT . '/users/auth', 'icon' => 'login']
            ];
        } 
        // Menu items for admin users
        else if (isset($_SESSION['user_account_type']) && $_SESSION['user_account_type'] === 'admin') {
            $menu = [
                ['title' => 'Dashboard', 'url' => URLROOT . '/dashboard', 'icon' => 'dashboard'],
                ['title' => 'Users', 'url' => URLROOT . '/dashboard/user_management', 'icon' => 'people'],
                ['title' => 'Content', 'url' => URLROOT . '/dashboard/blog_management', 'icon' => 'article'],
                ['title' => 'Support', 'url' => URLROOT . '/dashboard/support_tickets', 'icon' => 'contact_support'],
                ['title' => 'Settings', 'url' => URLROOT . '/dashboard/settings', 'icon' => 'settings'],
                ['title' => 'Logout', 'url' => URLROOT . '/users/logout', 'icon' => 'logout']
            ];
        }
        // Menu items for freelancer users
        else if (isset($_SESSION['user_account_type']) && $_SESSION['user_account_type'] === 'freelancer') {
            $menu = [
                ['title' => 'Dashboard', 'url' => URLROOT . '/freelance', 'icon' => 'dashboard'],
                ['title' => 'Projects', 'url' => URLROOT . '/freelance/projects', 'icon' => 'work'],
                ['title' => 'Profile', 'url' => URLROOT . '/users/profile', 'icon' => 'person'],
                ['title' => 'Messages', 'url' => URLROOT . '/freelance/messages', 'icon' => 'message'],
                ['title' => 'Settings', 'url' => URLROOT . '/users/settings', 'icon' => 'settings'],
                ['title' => 'Logout', 'url' => URLROOT . '/users/logout', 'icon' => 'logout']
            ];
        }
        // Menu items for client users
        else if (isset($_SESSION['user_account_type']) && $_SESSION['user_account_type'] === 'client') {
            $menu = [
                ['title' => 'Dashboard', 'url' => URLROOT . '/client', 'icon' => 'dashboard'],
                ['title' => 'Post a Job', 'url' => URLROOT . '/client/post-job', 'icon' => 'add_circle'],
                ['title' => 'My Projects', 'url' => URLROOT . '/client/projects', 'icon' => 'folder'],
                ['title' => 'Freelancers', 'url' => URLROOT . '/client/freelancers', 'icon' => 'people'],
                ['title' => 'Messages', 'url' => URLROOT . '/client/messages', 'icon' => 'message'],
                ['title' => 'Settings', 'url' => URLROOT . '/users/settings', 'icon' => 'settings'],
                ['title' => 'Logout', 'url' => URLROOT . '/users/logout', 'icon' => 'logout']
            ];
        }
        
        return $menu;
    }
} 