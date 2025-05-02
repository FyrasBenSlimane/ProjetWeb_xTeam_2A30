<?php
// Flash message helper
function flash($name = '', $message = '', $class = 'alert alert-success')
{
    if (!empty($name)) {
        if (!empty($message) && empty($_SESSION[$name])) {
            if (!empty($_SESSION[$name])) {
                unset($_SESSION[$name]);
            }

            if (!empty($_SESSION[$name . '_class'])) {
                unset($_SESSION[$name . '_class']);
            }

            $_SESSION[$name] = $message;
            $_SESSION[$name . '_class'] = $class;
        } elseif (empty($message) && !empty($_SESSION[$name])) {
            $class = !empty($_SESSION[$name . '_class']) ? $_SESSION[$name . '_class'] : '';
            echo '<div class="' . $class . '" id="msg-flash">' . $_SESSION[$name] . '</div>';
            unset($_SESSION[$name]);
            unset($_SESSION[$name . '_class']);
        }
    }
}

// Redirect helper
function redirect($page)
{
    header('location: ' . URL_ROOT . '/' . $page);
    exit;
}

// Check if user is logged in
function isLoggedIn()
{
    return isset($_SESSION['user_id']);
}

/**
 * Check if user is an admin
 * @return boolean
 */
function isAdmin()
{
    if (isset($_SESSION['user_account_type']) && $_SESSION['user_account_type'] == 'admin') {
        return true;
    }
    return false;
}

/**
 * Format file size in bytes to human-readable format
 * @param int $bytes File size in bytes
 * @param int $precision Number of decimal places
 * @return string Formatted file size with unit (KB, MB, etc.)
 */
function formatFileSize($bytes, $precision = 2)
{
    $units = ['B', 'KB', 'MB', 'GB', 'TB'];

    $bytes = max($bytes, 0);
    $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
    $pow = min($pow, count($units) - 1);

    $bytes /= (1 << (10 * $pow));

    return round($bytes, $precision) . ' ' . $units[$pow];
}

/**
 * Convert timestamp to elapsed time string (e.g., "2 hours ago")
 * @param string $timestamp MySQL timestamp
 * @return string
 */
function timeElapsed($timestamp)
{
    $datetime1 = new DateTime($timestamp);
    $datetime2 = new DateTime();
    $interval = $datetime1->diff($datetime2);

    if ($interval->y > 0) {
        return $interval->y . ' year' . ($interval->y > 1 ? 's' : '') . ' ago';
    } elseif ($interval->m > 0) {
        return $interval->m . ' month' . ($interval->m > 1 ? 's' : '') . ' ago';
    } elseif ($interval->d > 0) {
        if ($interval->d > 7) {
            return floor($interval->d / 7) . ' week' . (floor($interval->d / 7) > 1 ? 's' : '') . ' ago';
        } else {
            return $interval->d . ' day' . ($interval->d > 1 ? 's' : '') . ' ago';
        }
    } elseif ($interval->h > 0) {
        return $interval->h . ' hour' . ($interval->h > 1 ? 's' : '') . ' ago';
    } elseif ($interval->i > 0) {
        return $interval->i . ' minute' . ($interval->i > 1 ? 's' : '') . ' ago';
    } else {
        return 'Just now';
    }
}
