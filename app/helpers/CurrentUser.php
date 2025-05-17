<?php
/**
 * CurrentUser Helper Class
 * 
 * A static helper class to manage access to current logged-in user data
 * throughout the application.
 */
class CurrentUser {
    private static $userData = null;
    private static $userSettings = null;
    private static $isInitialized = false;
    
    /**
     * Initialize the current user data from the database
     * Call this method once in the application bootstrap
     * 
     * @return void
     */
    public static function initialize() {
        if (isset($_SESSION['user_id']) && !self::$isInitialized) {
            $userModel = new User();
            self::$userData = $userModel->getUserProfile($_SESSION['user_id']);
            self::$userSettings = $userModel->getUserSettings($_SESSION['user_id']);
            
            // Update last active timestamp
            $userModel->updateLastActive($_SESSION['user_id']);
            
            self::$isInitialized = true;
        }
    }
    
    /**
     * Get the entire user data object
     * 
     * @return object|null User data or null if not logged in
     */
    public static function getData() {
        self::ensureInitialized();
        return self::$userData;
    }
    
    /**
     * Get user settings
     * 
     * @return object|null User settings or null if not logged in
     */
    public static function getSettings() {
        self::ensureInitialized();
        return self::$userSettings;
    }
    
    /**
     * Get a specific setting
     * 
     * @param string $setting Setting name
     * @return mixed Setting value or null if not found
     */
    public static function getSetting($setting) {
        self::ensureInitialized();
        return self::$userSettings && isset(self::$userSettings->$setting) ? self::$userSettings->$setting : null;
    }
    
    /**
     * Check if user is logged in
     * 
     * @return bool True if logged in, false otherwise
     */
    public static function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
    
    /**
     * Get user ID
     * 
     * @return int|null User ID or null if not logged in
     */
    public static function getId() {
        return isset($_SESSION['user_id']) ? $_SESSION['user_id'] : null;
    }
    
    /**
     * Get user name
     * 
     * @return string|null User name or null if not logged in
     */
    public static function getName() {
        return isset($_SESSION['user_name']) ? $_SESSION['user_name'] : null;
    }
    
    /**
     * Get user email
     * 
     * @return string|null User email or null if not logged in
     */
    public static function getEmail() {
        return isset($_SESSION['user_email']) ? $_SESSION['user_email'] : null;
    }
    
    /**
     * Get user account type
     * 
     * @return string|null User account type or null if not logged in
     */
    public static function getAccountType() {
        return isset($_SESSION['user_account_type']) ? $_SESSION['user_account_type'] : null;
    }
    
    /**
     * Get a specific field from the user data
     * 
     * @param string $field Field name to get
     * @return mixed Field value or null if not found or not logged in
     */
    public static function get($field) {
        self::ensureInitialized();
        return self::$userData && isset(self::$userData->$field) ? self::$userData->$field : null;
    }
    
    /**
     * Check if current user is an admin
     * 
     * @return bool True if admin, false otherwise
     */
    public static function isAdmin() {
        return isset($_SESSION['user_account_type']) && $_SESSION['user_account_type'] === 'admin';
    }
    
    /**
     * Check if current user is a freelancer
     * 
     * @return bool True if freelancer, false otherwise
     */
    public static function isFreelancer() {
        return isset($_SESSION['user_account_type']) && $_SESSION['user_account_type'] === 'freelancer';
    }
    
    /**
     * Check if current user is a client
     * 
     * @return bool True if client, false otherwise
     */
    public static function isClient() {
        return isset($_SESSION['user_account_type']) && $_SESSION['user_account_type'] === 'client';
    }
    
    /**
     * Ensure the user data is initialized
     * 
     * @return void
     */
    private static function ensureInitialized() {
        if (!self::$isInitialized && isset($_SESSION['user_id'])) {
            self::initialize();
        }
    }
    
    /**
     * Refresh user data from the database
     * Call this after updating user profile
     * 
     * @return void
     */
    public static function refresh() {
        if (isset($_SESSION['user_id'])) {
            $userModel = new User();
            self::$userData = $userModel->getUserProfile($_SESSION['user_id']);
            self::$userSettings = $userModel->getUserSettings($_SESSION['user_id']);
            self::$isInitialized = true;
        }
    }
} 