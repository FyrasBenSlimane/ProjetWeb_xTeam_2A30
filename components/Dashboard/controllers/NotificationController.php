<?php
/**
 * Notification Controller
 * Handles all notification operations
 */
class NotificationController {
    private $notificationModel;
    
    /**
     * Constructor - Initialize models
     */
    public function __construct() {
        require_once __DIR__ . '/../models/NotificationModel.php';
        $this->notificationModel = new NotificationModel();
    }
    
    /**
     * Display notifications page
     */
    public function index() {
        // Get user email from session
        $userEmail = $_SESSION['user']['email'];
        
        // Check for expired notifications before displaying
        $this->notificationModel->checkAndUpdateExpiredNotifications($userEmail);
        
        // Get notifications
        $notifications = $this->notificationModel->getUserNotifications($userEmail, 50);
        
        // Debug log
        error_log("NotificationController::index - Retrieved " . count($notifications) . " notifications for $userEmail");
        foreach ($notifications as $idx => $notification) {
            $status = isset($notification['data']['status']) ? $notification['data']['status'] : 'undefined';
            error_log("Notification #$idx: Type={$notification['type']}, Status=$status, Title={$notification['title']}, Message={$notification['message']}");
        }
        
        // Include the view
        include_once __DIR__ . '/../user/notifications.php';
    }
    
    /**
     * Mark a notification as read
     * AJAX handler
     */
    public function markAsRead() {
        header('Content-Type: application/json');
        $response = ['success' => false];
        
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            echo json_encode($response);
            exit;
        }
        
        $userEmail = $_SESSION['user']['email'];
        
        // Get notification ID from POST or GET
        $notificationId = 0;
        if (isset($_POST['notification_id'])) {
            $notificationId = (int) $_POST['notification_id'];
        } elseif (isset($_GET['notification_id'])) {
            $notificationId = (int) $_GET['notification_id'];
        }
        
        error_log("markAsRead: notificationId = $notificationId, method = " . $_SERVER['REQUEST_METHOD']);
        
        if ($notificationId > 0) {
            try {
                // Mark notification as read
                $result = $this->notificationModel->markAsRead($notificationId, $userEmail);
                
                if ($result) {
                    $response['success'] = true;
                    $response['unread_count'] = $this->notificationModel->getUnreadCount($userEmail);
                    
                    // Get notification type to determine redirect if needed
                    $sql = "SELECT type, linked_id, data FROM notifications WHERE id = ? AND user_email = ?";
                    $stmt = $GLOBALS['pdo']->prepare($sql);
                    $stmt->execute([$notificationId, $userEmail]);
                    $notification = $stmt->fetch(PDO::FETCH_ASSOC);
                    
                    if ($notification) {
                        // Set redirect based on notification type
                        $data = !empty($notification['data']) ? json_decode($notification['data'], true) : [];
                        
                        if ($notification['type'] == 'candidature' && $notification['linked_id']) {
                            $candidatureId = $notification['linked_id'];
                            
                            if (isset($data['project_title'])) {
                                $response['redirect'] = "?page=projects&view=my-candidatures";
                            }
                        } elseif ($notification['type'] == 'project' && $notification['linked_id']) {
                            $projectId = $notification['linked_id'];
                            $response['redirect'] = "?page=projects&action=view&id=" . $projectId;
                        } elseif ($notification['type'] == 'message' && $notification['linked_id']) {
                            $messageId = $notification['linked_id'];
                            $response['redirect'] = "?page=messages&action=view&id=" . $messageId;
                        }
                    }
                } else {
                    $response['message'] = "Unable to mark notification as read.";
                }
            } catch (Exception $e) {
                error_log("NotificationController::markAsRead - Error: " . $e->getMessage());
                $response['message'] = "An error occurred while processing your request.";
            }
        } else {
            $response['message'] = "Invalid or missing notification ID.";
        }
        
        echo json_encode($response);
        exit;
    }
    
    /**
     * Mark all notifications as read
     * AJAX handler
     */
    public function markAllAsRead() {
        header('Content-Type: application/json');
        $response = ['success' => false];
        
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            echo json_encode($response);
            exit;
        }
        
        $userEmail = $_SESSION['user']['email'];
        
        error_log("markAllAsRead: method = " . $_SERVER['REQUEST_METHOD']);
        
        try {
            // Mark all notifications as read
            $result = $this->notificationModel->markAllAsRead($userEmail);
            
            if ($result) {
                $response['success'] = true;
                $response['unread_count'] = 0;
            } else {
                $response['message'] = "No notifications to mark as read.";
            }
        } catch (Exception $e) {
            error_log("NotificationController::markAllAsRead - Error: " . $e->getMessage());
            $response['message'] = "An error occurred while processing your request.";
        }
        
        echo json_encode($response);
        exit;
    }
    
    /**
     * Delete a notification
     * AJAX handler
     */
    public function deleteNotification() {
        header('Content-Type: application/json');
        $response = ['success' => false];
        
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            echo json_encode($response);
            exit;
        }
        
        $userEmail = $_SESSION['user']['email'];
        
        // Get notification ID from POST or GET
        $notificationId = 0;
        if (isset($_POST['notification_id'])) {
            $notificationId = (int) $_POST['notification_id'];
        } elseif (isset($_GET['notification_id'])) {
            $notificationId = (int) $_GET['notification_id'];
        }
        
        error_log("deleteNotification: notificationId = $notificationId, method = " . $_SERVER['REQUEST_METHOD']);
        
        if ($notificationId > 0) {
            // Delete notification
            $result = $this->notificationModel->deleteNotification($notificationId, $userEmail);
            
            if ($result) {
                $response['success'] = true;
                $response['unread_count'] = $this->notificationModel->getUnreadCount($userEmail);
            } else {
                $response['message'] = "Unable to delete notification.";
            }
        } else {
            $response['message'] = "Invalid or missing notification ID.";
        }
        
        echo json_encode($response);
        exit;
    }
    
    /**
     * Check for expired candidature notifications
     * AJAX handler for real-time updates
     */
    public function checkExpiredNotifications() {
        header('Content-Type: application/json');
        $response = ['success' => false];
        
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            echo json_encode($response);
            exit;
        }
        
        $userEmail = $_SESSION['user']['email'];
        
        try {
            // Check and update expired notifications
            $result = $this->notificationModel->checkAndUpdateExpiredNotifications($userEmail);
            
            if ($result) {
                $response['success'] = true;
                $response['unread_count'] = $this->notificationModel->getUnreadCount($userEmail);
            }
        } catch (Exception $e) {
            error_log("NotificationController::checkExpiredNotifications - Error: " . $e->getMessage());
            $response['message'] = "An error occurred while processing your request.";
        }
        
        echo json_encode($response);
        exit;
    }
    
    /**
     * Clean old notifications
     * This method can be called from a cron job
     */
    public function cleanOldNotifications() {
        // Clean notifications older than 30 days
        $this->notificationModel->cleanOldNotifications(30);
    }
    
    /**
     * Update expired candidature notifications (Non-AJAX version)
     * To be called directly from controllers
     */
    public function updateExpiredNotifications() {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            return false;
        }
        
        $userEmail = $_SESSION['user']['email'];
        
        try {
            // Check and update expired notifications
            return $this->notificationModel->checkAndUpdateExpiredNotifications($userEmail);
        } catch (Exception $e) {
            error_log("NotificationController::updateExpiredNotifications - Error: " . $e->getMessage());
            return false;
        }
    }
} 