<?php

/**
 * DashboardService Class
 * Service to manage dashboard state and provide helper functionality
 */
class DashboardService
{
    private $db = null;

    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Check if sidebar is open
     * 
     * @return bool
     */
    public function isSidebarOpen()
    {
        return isset($_SESSION['sidebar_open']) ? $_SESSION['sidebar_open'] : true;
    }

    /**
     * Toggle sidebar state
     * 
     * @return bool New sidebar state
     */
    public function toggleSidebar()
    {
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
    public function formatDate($dateString, $format = 'M j, Y')
    {
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
    public function logActivity($action, $module, $targetId = null)
    {
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
    public function getRecentActivityLogs($limit = 10)
    {
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
            if ($logs) {
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
    private function createActivityLogTableIfNeeded()
    {
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

                // Insert sample data if we have at least one user
                $this->db->query("SELECT id FROM users LIMIT 1");
                $user = $this->db->single();

                if ($user) {
                    $this->db->query("INSERT INTO activity_logs (user_id, action, module, target_id, ip_address, created_at) VALUES 
                        (:userId, 'login', 'auth', NULL, '127.0.0.1', NOW() - INTERVAL 2 HOUR),
                        (:userId, 'view', 'dashboard', NULL, '127.0.0.1', NOW() - INTERVAL 1 HOUR 55 MINUTE),
                        (:userId, 'update', 'user', 2, '127.0.0.1', NOW() - INTERVAL 1 HOUR 30 MINUTE),
                        (:userId, 'create', 'blog_post', 1, '127.0.0.1', NOW() - INTERVAL 50 MINUTE),
                        (:userId, 'update', 'settings', NULL, '127.0.0.1', NOW() - INTERVAL 20 MINUTE)");
                    $this->db->bind(':userId', $user->id);
                    $this->db->execute();
                }
            }
        } catch (Exception $e) {
            error_log('Error creating activity logs table: ' . $e->getMessage());
        }
    }

    /**
     * Get analytics data for dashboard
     * @return array
     */
    public function getAnalyticsData()
    {
        // Get total users count
        $this->db->query("SELECT COUNT(*) as total FROM users");
        $totalUsers = $this->db->single()->total;

        // Get active users count
        $this->db->query("SELECT COUNT(*) as active FROM users WHERE status = 'active'");
        $activeUsers = $this->db->single()->active;

        // Get today's visits
        $date = date('Y-m-d');
        $this->db->query("SELECT COUNT(*) as visits FROM user_visits WHERE DATE(visit_date) = :date");
        $this->db->bind(':date', $date);
        $todayVisits = $this->db->single()->visits ?? 0;

        // Get yesterday's visits for growth calculation
        $yesterday = date('Y-m-d', strtotime('-1 day'));
        $this->db->query("SELECT COUNT(*) as visits FROM user_visits WHERE DATE(visit_date) = :date");
        $this->db->bind(':date', $yesterday);
        $yesterdayVisits = $this->db->single()->visits ?? 0;

        // Calculate growth percentage
        $visitsGrowth = 0;
        if ($yesterdayVisits > 0) {
            $visitsGrowth = round((($todayVisits - $yesterdayVisits) / $yesterdayVisits) * 100);
        }

        return [
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'todayVisits' => $todayVisits,
            'visitsGrowth' => $visitsGrowth
        ];
    }

    /**
     * Get chart data for website visits
     * @param string $period
     * @return array
     */
    public function getVisitChartData($period = 'week')
    {
        $result = [];

        switch ($period) {
            case 'week':
                // Last 7 days
                for ($i = 6; $i >= 0; $i--) {
                    $date = date('Y-m-d', strtotime("-$i days"));
                    $day = date('D', strtotime("-$i days"));

                    $this->db->query("SELECT COUNT(*) as visits FROM user_visits WHERE DATE(visit_date) = :date");
                    $this->db->bind(':date', $date);
                    $visits = $this->db->single()->visits ?? 0;

                    $result[] = [
                        'day' => $day,
                        'date' => $date,
                        'value' => $visits
                    ];
                }
                break;

            case 'month':
                // Last 30 days in weekly chunks
                for ($i = 4; $i >= 0; $i--) {
                    $endDate = date('Y-m-d', strtotime("-" . ($i * 7) . " days"));
                    $startDate = date('Y-m-d', strtotime("-" . (($i * 7) + 6) . " days"));
                    $weekLabel = "Week " . (5 - $i);

                    $this->db->query("SELECT COUNT(*) as visits FROM user_visits WHERE DATE(visit_date) BETWEEN :start AND :end");
                    $this->db->bind(':start', $startDate);
                    $this->db->bind(':end', $endDate);
                    $visits = $this->db->single()->visits ?? 0;

                    $result[] = [
                        'day' => $weekLabel,
                        'date' => $startDate . ' to ' . $endDate,
                        'value' => $visits
                    ];
                }
                break;

            case 'year':
                // Last 12 months
                for ($i = 11; $i >= 0; $i--) {
                    $month = date('Y-m', strtotime("-$i months"));
                    $monthName = date('M', strtotime("-$i months"));

                    $this->db->query("SELECT COUNT(*) as visits FROM user_visits WHERE DATE_FORMAT(visit_date, '%Y-%m') = :month");
                    $this->db->bind(':month', $month);
                    $visits = $this->db->single()->visits ?? 0;

                    $result[] = [
                        'day' => $monthName,
                        'date' => $month,
                        'value' => $visits
                    ];
                }
                break;
        }

        return $result;
    }

    /**
     * Get user distribution data for charts
     * @return array
     */
    public function getUserDistributionData()
    {
        $this->db->query("SELECT role, COUNT(*) as count FROM users GROUP BY role");
        $results = $this->db->resultSet();

        $distribution = [];
        foreach ($results as $row) {
            $distribution[] = [
                'role' => ucfirst($row->role),
                'count' => $row->count
            ];
        }

        return $distribution;
    }

    /**
     * Get recent users for dashboard
     * @param int $limit
     * @return array
     */
    public function getRecentUsers($limit = 5)
    {
        $this->db->query("SELECT id, name, email, role, created_at, status FROM users ORDER BY created_at DESC LIMIT :limit");
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $users = $this->db->resultSet();

        $formattedUsers = [];
        foreach ($users as $user) {
            $formattedUsers[] = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => ucfirst($user->role),
                'registeredDate' => $user->created_at,
                'status' => ucfirst($user->status ?? 'active')
            ];
        }

        return $formattedUsers;
    }

    /**
     * Get recent activity logs
     * @param int $limit
     * @return array
     */
    public function getActivityLogs($limit = 5)
    {
        // Check if activity_logs table exists
        $this->db->query("SHOW TABLES LIKE 'activity_logs'");
        $tableExists = $this->db->rowCount() > 0;

        if (!$tableExists) {
            // Create a sample result if the table doesn't exist
            $sampleData = [];
            $actions = ['login', 'create', 'update', 'delete'];
            $modules = ['auth', 'user', 'dashboard', 'blog_post', 'settings'];
            $userNames = ['John Doe', 'Jane Smith', 'Admin User'];

            for ($i = 0; $i < $limit; $i++) {
                $timestamp = date('Y-m-d H:i:s', strtotime("-" . rand(1, 24) . " hours"));
                $sampleData[] = [
                    'userName' => $userNames[array_rand($userNames)],
                    'action' => $actions[array_rand($actions)],
                    'module' => $modules[array_rand($modules)],
                    'timestamp' => $timestamp,
                    'formattedDate' => $this->getTimeAgo($timestamp)
                ];
            }

            return $sampleData;
        }

        // Get real activity logs if table exists
        $this->db->query("SELECT al.*, u.name as user_name 
                        FROM activity_logs al 
                        LEFT JOIN users u ON al.user_id = u.id 
                        ORDER BY al.created_at DESC 
                        LIMIT :limit");
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        $logs = $this->db->resultSet();

        $formattedLogs = [];
        foreach ($logs as $log) {
            $formattedLogs[] = [
                'userName' => $log->user_name ?? 'System',
                'action' => $log->action,
                'module' => $log->module,
                'timestamp' => $log->created_at,
                'formattedDate' => $this->getTimeAgo($log->created_at)
            ];
        }

        return $formattedLogs;
    }

    /**
     * Get time ago string from timestamp
     * @param string $timestamp
     * @return string
     */
    private function getTimeAgo($timestamp)
    {
        $time = strtotime($timestamp);
        $now = time();
        $diff = $now - $time;

        if ($diff < 60) {
            return 'Just now';
        } elseif ($diff < 3600) {
            $mins = round($diff / 60);
            return $mins . ' minute' . ($mins > 1 ? 's' : '') . ' ago';
        } elseif ($diff < 86400) {
            $hours = round($diff / 3600);
            return $hours . ' hour' . ($hours > 1 ? 's' : '') . ' ago';
        } elseif ($diff < 604800) {
            $days = round($diff / 86400);
            return $days . ' day' . ($days > 1 ? 's' : '') . ' ago';
        } else {
            return date('M j, Y', $time);
        }
    }

    /**
     * Get number of unread support tickets
     * @return int
     */
    public function getUnreadTicketsCount()
    {
        // Check if support_tickets table exists
        $this->db->query("SHOW TABLES LIKE 'support_tickets'");
        if ($this->db->rowCount() == 0) {
            return 0;
        }

        $this->db->query("SELECT COUNT(*) as count FROM support_tickets WHERE status = 'unread' OR status = 'open'");
        return $this->db->single()->count ?? 0;
    }
}
