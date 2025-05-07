<?php
/**
 * DashboardService Class
 * Singleton service to manage dashboard state and provide helper functionality
 */
class DashboardService {
    private static $instance = null;
    private $dashboardController = null;
    private $db = null;
    
    private function __construct() {
        // Private constructor to enforce singleton pattern
        $this->db = new Database();
    }
    
    /**
     * Get the singleton instance
     * 
     * @return DashboardService
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new DashboardService();
        }
        return self::$instance;
    }
    
    /**
     * Get the dashboard controller
     * 
     * @return DashboardController
     */
    public function getController() {
        if ($this->dashboardController === null) {
            $this->dashboardController = new DashboardController();
        }
        return $this->dashboardController;
    }
    
    /**
     * Check if sidebar is open
     * 
     * @return bool
     */
    public function isSidebarOpen() {
        return isset($_SESSION['sidebar_open']) ? $_SESSION['sidebar_open'] : true;
    }
    
    /**
     * Toggle sidebar state
     * 
     * @return bool New sidebar state
     */
    public function toggleSidebar() {
        $_SESSION['sidebar_open'] = !$this->isSidebarOpen();
        return $_SESSION['sidebar_open'];
    }
    
    /**
     * Get formatted date
     * 
     * @param string $dateString Date string in ISO format
     * @param string $format PHP date format
     * @return string Formatted date
     */
    public function formatDate($dateString, $format = 'M j, Y') {
        $date = new DateTime($dateString);
        return $date->format($format);
    }
    
    /**
     * Generate activity log entry
     * 
     * @param string $action The action performed
     * @param string $module The module where the action was performed
     * @param int $targetId ID of the target entity (optional)
     * @return bool Success status
     */
    public function logActivity($action, $module, $targetId = null) {
        if (!$this->db) {
            $this->db = new Database();
        }
        
        // Create activity_logs table if it doesn't exist
        $this->createActivityLogTableIfNeeded();
        
        try {
            $this->db->query("INSERT INTO activity_logs (user_id, action, module, target_id, ip_address) 
                            VALUES (:userId, :action, :module, :targetId, :ipAddress)");
            $this->db->bind(':userId', $_SESSION['user_id'] ?? null);
            $this->db->bind(':action', $action);
            $this->db->bind(':module', $module);
            $this->db->bind(':targetId', $targetId);
            $this->db->bind(':ipAddress', $_SERVER['REMOTE_ADDR'] ?? null);
            
            return $this->db->execute();
        } catch (Exception $e) {
            error_log('Error logging activity: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get recent activity logs for the dashboard
     * 
     * @param int $limit Number of logs to retrieve
     * @return array Activity logs
     */
    public function getRecentActivityLogs($limit = 10) {
        if (!$this->db) {
            $this->db = new Database();
        }
        
        // Create activity_logs table if it doesn't exist
        $this->createActivityLogTableIfNeeded();
        
        try {
            $this->db->query("SELECT l.*, u.name as userName 
                            FROM activity_logs l
                            LEFT JOIN users u ON l.user_id = u.id
                            ORDER BY l.created_at DESC
                            LIMIT :limit");
            $this->db->bind(':limit', $limit, PDO::PARAM_INT);
            
            $logs = $this->db->resultSet();
            
            // Format logs for display
            $formattedLogs = [];
            foreach ($logs as $log) {
                $formattedLogs[] = [
                    'id' => $log->id,
                    'userName' => $log->userName ?? 'System',
                    'action' => $log->action,
                    'module' => $log->module,
                    'targetId' => $log->target_id,
                    'ipAddress' => $log->ip_address,
                    'createdAt' => $log->created_at,
                    'formattedDate' => $this->formatDate($log->created_at, 'M j, Y H:i')
                ];
            }
            
            return $formattedLogs;
        } catch (Exception $e) {
            error_log('Error getting activity logs: ' . $e->getMessage());
            return [];
        }
    }
    
    /**
     * Create activity logs table if it doesn't exist
     */
    private function createActivityLogTableIfNeeded() {
        try {
            $this->db->query("SHOW TABLES LIKE 'activity_logs'");
            $tableExists = $this->db->resultSet();
            
            if (empty($tableExists)) {
                $this->db->query("CREATE TABLE activity_logs (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    user_id INT NULL,
                    action VARCHAR(255) NOT NULL,
                    module VARCHAR(100) NOT NULL,
                    target_id INT NULL,
                    ip_address VARCHAR(45) NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
                )");
                $this->db->execute();
                
                // Insert sample data
                $this->db->query("INSERT INTO activity_logs (user_id, action, module, target_id, ip_address, created_at) VALUES 
                    (1, 'login', 'auth', NULL, '127.0.0.1', NOW() - INTERVAL 2 HOUR),
                    (1, 'view', 'dashboard', NULL, '127.0.0.1', NOW() - INTERVAL 1 HOUR 55 MINUTE),
                    (1, 'update', 'user', 2, '127.0.0.1', NOW() - INTERVAL 1 HOUR 30 MINUTE),
                    (1, 'create', 'blog_post', 1, '127.0.0.1', NOW() - INTERVAL 50 MINUTE),
                    (1, 'update', 'settings', NULL, '127.0.0.1', NOW() - INTERVAL 20 MINUTE)");
                $this->db->execute();
            }
        } catch (Exception $e) {
            error_log('Error creating activity logs table: ' . $e->getMessage());
        }
    }
}