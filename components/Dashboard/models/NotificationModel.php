    <?php
/**
 * Notification Model
 * Handles database operations for user notifications
 */
class NotificationModel {
    private $db;

    public function __construct($db = null) {
        if ($db) {
            $this->db = $db;
        } else {
            // Get database connection
            require_once __DIR__ . '/../../../config/database.php';
            $this->db = $GLOBALS['pdo'] ?? getDBConnection();
        }
        
        // Vérifier si la table notifications existe
        $this->ensureTableExists();
    }

    /**
     * Vérifie si la table notifications existe et la crée si nécessaire
     */
    private function ensureTableExists() {
        try {
            // Vérifier si la table existe
            $sql = "SHOW TABLES LIKE 'notifications'";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
            
            if ($stmt->rowCount() === 0) {
                // La table n'existe pas, la créer
                $sql = "CREATE TABLE IF NOT EXISTS `notifications` (
                    `id` int(11) NOT NULL AUTO_INCREMENT,
                    `user_email` varchar(255) NOT NULL,
                    `type` varchar(50) NOT NULL,
                    `title` varchar(255) NOT NULL,
                    `message` text NOT NULL,
                    `data` text DEFAULT NULL,
                    `linked_id` varchar(255) DEFAULT NULL,
                    `is_read` tinyint(1) NOT NULL DEFAULT 0,
                    `is_expired` tinyint(1) NOT NULL DEFAULT 0,
                    `created_at` datetime NOT NULL,
                    `expires_at` datetime NULL,
                    PRIMARY KEY (`id`),
                    KEY `user_email` (`user_email`),
                    KEY `is_read` (`is_read`),
                    KEY `created_at` (`created_at`),
                    KEY `expires_at` (`expires_at`),
                    UNIQUE KEY `unique_notification` (`user_email`, `type`, `linked_id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;";
                
                $this->db->exec($sql);
                error_log("NotificationModel: Table notifications créée avec succès.");
            }
        } catch (PDOException $e) {
            error_log("NotificationModel::ensureTableExists - Error: " . $e->getMessage());
        }
    }

    /**
     * Add a notification for a user
     * 
     * @param string $userEmail The recipient's email
     * @param string $type The notification type (project, message, system, reject)
     * @param string $title The notification title
     * @param string $message The notification message
     * @param string $linkedId Related ID (project_id, message_id, etc.)
     * @param array $data Additional data to store with the notification
     * @param string $expiresAt Expiration date in format Y-m-d H:i:s (optional)
     * @param bool $allowDuplicates Whether to allow duplicate notifications for the same type and linked_id
     * @return int|bool The new notification ID or false on failure
     */
    public function addNotification($userEmail, $type, $title, $message, $linkedId = null, $data = null, $expiresAt = null, $allowDuplicates = false) {
        // Vérifier si l'utilisateur existe
        $stmt = $this->db->prepare("SELECT email FROM users WHERE email = ?");
        $stmt->execute([$userEmail]);
        if (!$stmt->fetch()) {
            error_log("NotificationModel::addNotification - User not found: $userEmail");
            return false;
        }
    
        try {
            // Démarrer une transaction pour garantir l'atomicité
            $this->db->beginTransaction();
    
            // Vérifier les doublons, sauf si allowDuplicates est true
            if (!$allowDuplicates) {
                $checkSql = "SELECT id FROM notifications 
                             WHERE user_email = ? AND type = ? 
                             AND ((linked_id = ? AND ? IS NOT NULL) OR (linked_id IS NULL AND ? IS NULL))
                             AND is_expired = 0 
                             AND (expires_at IS NULL OR expires_at > NOW())";
                $checkStmt = $this->db->prepare($checkSql);
                $checkStmt->execute([$userEmail, $type, $linkedId, $linkedId, $linkedId]);
    
                if ($checkStmt->fetch()) {
                    $this->db->rollBack();
                    error_log("NotificationModel::addNotification - Duplicate detected for user: $userEmail, type: $type, linked_id: " . ($linkedId ?? 'NULL'));
                    return false; // Doublon détecté
                }
            }
    
            // Insérer la notification
            $sql = "INSERT INTO notifications (user_email, type, title, message, linked_id, data, expires_at, created_at) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
    
            // Convertir data en JSON
            $jsonData = null;
            if ($data !== null) {
                $jsonData = json_encode($data, JSON_THROW_ON_ERROR);
            }
    
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userEmail, $type, $title, $message, $linkedId, $jsonData, $expiresAt]);
    
            $lastInsertId = $this->db->lastInsertId();
            $this->db->commit();
    
            return $lastInsertId;
        } catch (PDOException | JsonException $e) {
            $this->db->rollBack();
            if ($e instanceof PDOException && $e->getCode() == '23000') {
                error_log("NotificationModel::addNotification - Duplicate entry detected for user: $userEmail, type: $type, linked_id: " . ($linkedId ?? 'NULL'));
            } else {
                error_log("NotificationModel::addNotification - Error for user: $userEmail, type: $type, linked_id: " . ($linkedId ?? 'NULL') . ", message: " . $e->getMessage());
            }
            return false;
        }
    }  

    /**
     * Get notifications for a user
     * 
     * @param string $userEmail The user's email
     * @param int $limit Maximum number of notifications to return
     * @param bool $unreadOnly Whether to return only unread notifications
     * @return array Notifications data
     */
    public function getUserNotifications($userEmail, $limit = 10, $unreadOnly = false) {
        try {
            $sql = "SELECT * FROM notifications WHERE user_email = ?";
            
            if ($unreadOnly) {
                $sql .= " AND is_read = 0";
            }
            
            $sql .= " ORDER BY created_at DESC LIMIT ?";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userEmail, $limit]);
            
            $notifications = $stmt->fetchAll(PDO::FETCH_ASSOC);
            
            // Decode JSON data field for each notification
            foreach ($notifications as &$notification) {
                if (!empty($notification['data'])) {
                    $notification['data'] = json_decode($notification['data'], true) ?: [];
                } else {
                    $notification['data'] = [];
                }
            }
            
            return $notifications;
        } catch (Exception $e) {
            error_log("NotificationModel::getUserNotifications - Error for user: $userEmail, message: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get the count of unread notifications
     * 
     * @param string $userEmail The user's email
     * @return int Number of unread notifications
     */
    public function getUnreadCount($userEmail) {
        try {
            $sql = "SELECT COUNT(*) as count FROM notifications 
                    WHERE user_email = ? AND is_read = 0 AND is_expired = 0";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userEmail]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return (int) $result['count'];
        } catch (PDOException $e) {
            error_log("NotificationModel::getUnreadCount - Error for user: $userEmail, message: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Mark a notification as read
     * 
     * @param int $notificationId The notification ID
     * @param string $userEmail The user's email (for security check)
     * @return bool Success status
     */
    public function markAsRead($notificationId, $userEmail) {
        try {
            $sql = "UPDATE notifications SET is_read = 1 WHERE id = ? AND user_email = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$notificationId, $userEmail]);
        } catch (Exception $e) {
            error_log("NotificationModel::markAsRead - Error for notification: $notificationId, user: $userEmail, message: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Mark all notifications as read for a user
     * 
     * @param string $userEmail The user's email
     * @return bool Success status
     */
    public function markAllAsRead($userEmail) {
        try {
            $sql = "UPDATE notifications SET is_read = 1 WHERE user_email = ? AND is_read = 0";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$userEmail]);
        } catch (Exception $e) {
            error_log("NotificationModel::markAllAsRead - Error for user: $userEmail, message: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a notification
     * 
     * @param int $notificationId The notification ID
     * @param string $userEmail The user's email (for security check)
     * @return bool Success status
     */
    public function deleteNotification($notificationId, $userEmail) {
        try {
            $sql = "DELETE FROM notifications WHERE id = ? AND user_email = ?";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$notificationId, $userEmail]);
        } catch (Exception $e) {
            error_log("NotificationModel::deleteNotification - Error for notification: $notificationId, user: $userEmail, message: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete old notifications (older than specified days)
     * 
     * @param int $days Number of days to keep notifications
     * @return bool Success status
     */
    public function cleanOldNotifications($days = 30) {
        try {
            $sql = "DELETE FROM notifications WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute([$days]);
        } catch (Exception $e) {
            error_log("NotificationModel::cleanOldNotifications - Error: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Check for expired notifications and update their status
     * 
     * @param string $userEmail The user's email
     * @return bool True if operation was successful
     */
    public function checkAndUpdateExpiredNotifications($userEmail) {
        try {
            // Mettre à jour les notifications expirées
            $sql = "UPDATE notifications 
                    SET is_expired = 1, 
                        data = JSON_SET(COALESCE(data, '{}'), '$.status', 'expired')
                    WHERE user_email = ? 
                    AND expires_at IS NOT NULL 
                    AND expires_at <= NOW() 
                    AND is_expired = 0";
            
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userEmail]);
            
            return true;
        } catch (PDOException $e) {
            error_log("NotificationModel::checkAndUpdateExpiredNotifications - Error for user: $userEmail, message: " . $e->getMessage());
            return false;
        }
    }
}
?>