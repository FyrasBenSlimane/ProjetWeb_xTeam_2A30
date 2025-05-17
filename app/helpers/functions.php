<?php
// Flash message helper
function flash($name = '', $message = '', $class = 'alert alert-success') {
    if(!empty($name)) {
        if(!empty($message) && empty($_SESSION[$name])) {
            if(!empty($_SESSION[$name])) {
                unset($_SESSION[$name]);
            }

            if(!empty($_SESSION[$name . '_class'])) {
                unset($_SESSION[$name . '_class']);
            }

            $_SESSION[$name] = $message;
            $_SESSION[$name . '_class'] = $class;
        } elseif(empty($message) && !empty($_SESSION[$name])) {
            $class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : '';
            echo '<div class="' . $class . '" id="msg-flash">' . $_SESSION[$name] . '</div>';
            unset($_SESSION[$name]);
            unset($_SESSION[$name . '_class']);
        }
    }
}

// Redirect helper
function redirect($page) {
    header('location: ' . URL_ROOT . '/' . $page);
    exit;
}

// Check if user is logged in
function isLoggedIn() {
    return CurrentUser::isLoggedIn();
}

/**
 * Get current user information
 * 
 * @param string|null $field Optional specific field to retrieve
 * @return mixed User data, specific field value, or null if not logged in
 */
function user($field = null) {
    if ($field) {
        return CurrentUser::get($field);
    }
    return CurrentUser::getData();
}

/**
 * Check if current user is a freelancer
 * 
 * @return bool True if freelancer, false otherwise
 */
function isFreelancer() {
    return CurrentUser::isFreelancer();
}

/**
 * Check if current user is a client
 * 
 * @return bool True if client, false otherwise
 */
function isClient() {
    return CurrentUser::isClient();
}

/**
 * Check if current user is an admin
 * 
 * @return bool True if admin, false otherwise
 */
function isAdmin() {
    return CurrentUser::isAdmin();
}

/**
 * Get user settings
 * 
 * @param string|null $setting Optional specific setting to retrieve
 * @return mixed User settings, specific setting value, or null if not logged in
 */
function userSettings($setting = null) {
    if ($setting) {
        return CurrentUser::getSetting($setting);
    }
    return CurrentUser::getSettings();
}

/**
 * Format a timestamp as "time ago" (e.g., "5 minutes ago", "2 days ago")
 * 
 * @param string $datetime Datetime string to format
 * @return string Formatted time ago string
 */
function timeAgo($datetime) {
    if (!$datetime) {
        return 'Unknown time';
    }
    
    $timestamp = strtotime($datetime);
    
    if (!$timestamp) {
        return 'Invalid date';
    }
    
    $now = time();
    $diff = $now - $timestamp;
    
    // Define time periods in seconds
    $minute = 60;
    $hour = $minute * 60;
    $day = $hour * 24;
    $week = $day * 7;
    $month = $day * 30;
    $year = $day * 365;
    
    // Format the time ago string
    if ($diff < $minute) {
        return $diff == 1 ? "1 second ago" : "$diff seconds ago";
    } elseif ($diff < $hour) {
        $minutes = floor($diff / $minute);
        return $minutes == 1 ? "1 minute ago" : "$minutes minutes ago";
    } elseif ($diff < $day) {
        $hours = floor($diff / $hour);
        return $hours == 1 ? "1 hour ago" : "$hours hours ago";
    } elseif ($diff < $week) {
        $days = floor($diff / $day);
        return $days == 1 ? "1 day ago" : "$days days ago";
    } elseif ($diff < $month) {
        $weeks = floor($diff / $week);
        return $weeks == 1 ? "1 week ago" : "$weeks weeks ago";
    } elseif ($diff < $year) {
        $months = floor($diff / $month);
        return $months == 1 ? "1 month ago" : "$months months ago";
    } else {
        $years = floor($diff / $year);
        return $years == 1 ? "1 year ago" : "$years years ago";
    }
}