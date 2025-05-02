<?php

/**
 * DashboardModel Class
 * Handles data for the dashboard panels
 */
class DashboardModel
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    /**
     * Get analytics data for the dashboard
     * 
     * @return array Analytics data
     */
    public function getAnalyticsData()
    {
        // Get total users count
        $this->db->query("SELECT COUNT(*) as total FROM users");
        $totalUsersResult = $this->db->single();
        $totalUsers = $totalUsersResult ? $totalUsersResult->total : 0;

        // Get active users count (users who have logged in within last 30 days)
        $this->db->query("SELECT COUNT(*) as active FROM users WHERE updated_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
        $activeUsersResult = $this->db->single();
        $activeUsers = $activeUsersResult ? $activeUsersResult->active : 0;

        // Get visit history for the last 7 days
        $visitHistory = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));

            // Query to count visits for this date (from activity_logs or using a dedicated visits table if available)
            $this->db->query("SELECT COUNT(*) as visits FROM activity_logs WHERE DATE(created_at) = :date AND action = 'view' AND module = 'dashboard'");
            $this->db->bind(':date', $date);
            $visitResult = $this->db->single();

            $visits = $visitResult ? $visitResult->visits : 0;
            $visitHistory[] = [
                'date' => $date,
                'visits' => $visits
            ];
        }

        // Get today's visits
        $todayVisits = end($visitHistory)['visits'];

        // Get yesterday's visits to calculate growth
        $yesterday = prev($visitHistory);
        $yesterdayVisits = $yesterday ? $yesterday['visits'] : 0;
        $visitsGrowth = $yesterdayVisits > 0 ? round((($todayVisits - $yesterdayVisits) / $yesterdayVisits) * 100) : 0;

        // Get user distribution by role
        $this->db->query("SELECT account_type as role, COUNT(*) as count FROM users GROUP BY account_type");
        $userDistributionResults = $this->db->resultSet();

        $userDistribution = [];
        if ($userDistributionResults) {
            foreach ($userDistributionResults as $result) {
                $userDistribution[] = [
                    'role' => ucfirst($result->role),
                    'count' => $result->count
                ];
            }
        }

        return [
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'todayVisits' => $todayVisits,
            'visitsGrowth' => $visitsGrowth,
            'visitHistory' => $visitHistory,
            'userDistribution' => $userDistribution
        ];
    }

    /**
     * Get users data for the user management page
     * 
     * @return array List of users
     */
    public function getUsersData()
    {
        $this->db->query("SELECT id, name, email, account_type as role, 
                          IF(updated_at >= DATE_SUB(NOW(), INTERVAL 30 DAY), 'Active', 'Inactive') as status,
                          updated_at as lastLogin, created_at as registeredDate
                          FROM users
                          ORDER BY id DESC");

        $users = $this->db->resultSet();

        // Convert to array format expected by the frontend
        $formattedUsers = [];
        if ($users) {
            foreach ($users as $user) {
                $formattedUsers[] = [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => ucfirst($user->role),
                    'status' => $user->status,
                    'lastLogin' => $user->lastLogin,
                    'registeredDate' => $user->registeredDate
                ];
            }
        }

        return $formattedUsers;
    }

    /**
     * Get user by ID
     * 
     * @param int $userId User ID
     * @return array|bool User data or false if not found
     */
    public function getUserById($userId)
    {
        $this->db->query("SELECT id, name, email, account_type as role, 
                          IF(updated_at >= DATE_SUB(NOW(), INTERVAL 30 DAY), 'Active', 'Inactive') as status,
                          updated_at as lastLogin, created_at as registeredDate
                          FROM users
                          WHERE id = :userId");

        $this->db->bind(':userId', $userId);
        $user = $this->db->single();

        if (!$user) {
            return false;
        }

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => ucfirst($user->role),
            'status' => $user->status,
            'lastLogin' => $user->lastLogin,
            'registeredDate' => $user->registeredDate
        ];
    }

    /**
     * Update user status
     * 
     * @param int $userId User ID
     * @param string $status New status
     * @return bool Success status
     */
    public function updateUserStatus($userId, $status)
    {
        // Convert 'Active'/'Inactive' to appropriate database representation
        // For our system, we'll mark a user as active/inactive by updating the updated_at field
        if ($status == 'Active') {
            $this->db->query("UPDATE users SET updated_at = NOW() WHERE id = :userId");
        } else {
            // For inactive, we'll set updated_at to a date older than 30 days
            $this->db->query("UPDATE users SET updated_at = DATE_SUB(NOW(), INTERVAL 31 DAY) WHERE id = :userId");
        }

        $this->db->bind(':userId', $userId);
        return $this->db->execute();
    }

    /**
     * Delete user
     * 
     * @param int $userId User ID
     * @return bool Success status
     */
    public function deleteUser($userId)
    {
        $this->db->query("DELETE FROM users WHERE id = :userId");
        $this->db->bind(':userId', $userId);
        return $this->db->execute();
    }

    /**
     * Get blog posts data
     * 
     * @return array List of blog posts
     */
    public function getBlogPostsData()
    {
        // This query assumes you have a blog_posts table and a blog_comments table
        // Create these tables if they don't exist
        $this->createBlogTablesIfNeeded();

        // Get all blog posts with author information
        $this->db->query("SELECT p.*, u.name as authorName 
                          FROM blog_posts p
                          LEFT JOIN users u ON p.author_id = u.id
                          ORDER BY p.created_at DESC");

        $posts = $this->db->resultSet();
        $formattedPosts = [];

        if ($posts) {
            foreach ($posts as $post) {
                // Get comments for this post
                $this->db->query("SELECT c.*, u.name as userName 
                                FROM blog_comments c
                                LEFT JOIN users u ON c.user_id = u.id
                                WHERE c.post_id = :postId
                                ORDER BY c.created_at ASC");
                $this->db->bind(':postId', $post->id);
                $comments = $this->db->resultSet();

                $formattedComments = [];
                if ($comments) {
                    foreach ($comments as $comment) {
                        // Get responses to this comment
                        $this->db->query("SELECT r.*, u.name as userName 
                                        FROM blog_comment_responses r
                                        LEFT JOIN users u ON r.user_id = u.id
                                        WHERE r.comment_id = :commentId
                                        ORDER BY r.created_at ASC");
                        $this->db->bind(':commentId', $comment->id);
                        $responses = $this->db->resultSet();

                        $formattedResponses = [];
                        if ($responses) {
                            foreach ($responses as $response) {
                                $formattedResponses[] = [
                                    'id' => $response->id,
                                    'postId' => $post->id,
                                    'userId' => $response->user_id,
                                    'userName' => $response->userName,
                                    'content' => $response->content,
                                    'createdAt' => $response->created_at
                                ];
                            }
                        }

                        $formattedComments[] = [
                            'id' => $comment->id,
                            'postId' => $post->id,
                            'userId' => $comment->user_id,
                            'userName' => $comment->userName,
                            'content' => $comment->content,
                            'status' => $comment->status,
                            'createdAt' => $comment->created_at,
                            'responses' => $formattedResponses
                        ];
                    }
                }

                // Get tags for this post
                $this->db->query("SELECT t.name 
                                FROM blog_post_tags pt
                                JOIN blog_tags t ON pt.tag_id = t.id
                                WHERE pt.post_id = :postId");
                $this->db->bind(':postId', $post->id);
                $tagsResult = $this->db->resultSet();

                $tags = [];
                if ($tagsResult) {
                    foreach ($tagsResult as $tag) {
                        $tags[] = $tag->name;
                    }
                }

                $formattedPosts[] = [
                    'id' => $post->id,
                    'title' => $post->title,
                    'content' => $post->content,
                    'authorId' => $post->author_id,
                    'authorName' => $post->authorName,
                    'status' => $post->status,
                    'publishedAt' => $post->published_at,
                    'createdAt' => $post->created_at,
                    'updatedAt' => $post->updated_at,
                    'tags' => $tags,
                    'comments' => $formattedComments
                ];
            }
        }

        return $formattedPosts;
    }

    /**
     * Create blog tables if they don't exist
     */
    private function createBlogTablesIfNeeded()
    {
        // Check if blog_posts table exists, if not create the necessary tables
        $this->db->query("SHOW TABLES LIKE 'blog_posts'");
        $tableExists = $this->db->resultSet();

        if (empty($tableExists)) {
            // Create blog_posts table
            $this->db->query("CREATE TABLE blog_posts (
                id INT AUTO_INCREMENT PRIMARY KEY,
                title VARCHAR(255) NOT NULL,
                content TEXT NOT NULL,
                author_id INT NOT NULL,
                status ENUM('draft', 'published', 'archived') DEFAULT 'draft',
                published_at TIMESTAMP NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (author_id) REFERENCES users(id) ON DELETE CASCADE
            )");
            $this->db->execute();

            // Create blog_comments table
            $this->db->query("CREATE TABLE blog_comments (
                id INT AUTO_INCREMENT PRIMARY KEY,
                post_id INT NOT NULL,
                user_id INT NOT NULL,
                content TEXT NOT NULL,
                status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (post_id) REFERENCES blog_posts(id) ON DELETE CASCADE,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )");
            $this->db->execute();

            // Create blog_comment_responses table
            $this->db->query("CREATE TABLE blog_comment_responses (
                id INT AUTO_INCREMENT PRIMARY KEY,
                comment_id INT NOT NULL,
                user_id INT NOT NULL,
                content TEXT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (comment_id) REFERENCES blog_comments(id) ON DELETE CASCADE,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )");
            $this->db->execute();

            // Create blog_tags table
            $this->db->query("CREATE TABLE blog_tags (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(50) NOT NULL UNIQUE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )");
            $this->db->execute();

            // Create blog_post_tags table (many-to-many relationship)
            $this->db->query("CREATE TABLE blog_post_tags (
                post_id INT NOT NULL,
                tag_id INT NOT NULL,
                PRIMARY KEY (post_id, tag_id),
                FOREIGN KEY (post_id) REFERENCES blog_posts(id) ON DELETE CASCADE,
                FOREIGN KEY (tag_id) REFERENCES blog_tags(id) ON DELETE CASCADE
            )");
            $this->db->execute();

            // Insert sample data
            $this->insertSampleBlogData();
        }
    }

    /**
     * Insert sample blog data
     */
    private function insertSampleBlogData()
    {
        // First make sure we have at least one user for the sample posts
        $this->db->query("SELECT id FROM users LIMIT 1");
        $user = $this->db->single();

        if (!$user) {
            // Create a sample user if no users exist
            $this->db->query("INSERT INTO users (name, email, password, account_type, terms_accepted) 
                             VALUES ('Admin User', 'admin@example.com', :password, 'admin', 1)");
            $this->db->bind(':password', password_hash('admin123', PASSWORD_DEFAULT));
            $this->db->execute();

            $this->db->query("SELECT id FROM users LIMIT 1");
            $user = $this->db->single();
        }

        // Insert sample blog posts
        $this->db->query("INSERT INTO blog_posts (title, content, author_id, status, published_at, created_at, updated_at) VALUES 
            ('Getting Started with Our Platform', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', :userId, 'published', '2025-04-10 10:30:00', '2025-04-05 14:22:10', '2025-04-10 10:30:00'),
            ('Advanced Features Tutorial', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nisl eget ultricies tincidunt.', :userId, 'draft', NULL, '2025-04-20 16:15:40', '2025-04-25 09:10:30'),
            ('Monitoring Performance', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nisl eget ultricies tincidunt.', :userId, 'published', '2025-04-22 11:30:00', '2025-04-18 13:45:20', '2025-04-22 11:30:00')");
        $this->db->bind(':userId', $user->id);
        $this->db->execute();

        // Insert sample tags
        $this->db->query("INSERT INTO blog_tags (name) VALUES 
            ('Getting Started'), ('Guide'), ('Tutorial'), ('Security'), ('Advanced'), ('Features'), ('Performance'), ('Monitoring')");
        $this->db->execute();

        // Get blog post IDs
        $this->db->query("SELECT id FROM blog_posts ORDER BY id LIMIT 3");
        $posts = $this->db->resultSet();

        if ($posts && count($posts) >= 3) {
            // Associate tags with posts
            $this->db->query("INSERT INTO blog_post_tags (post_id, tag_id) VALUES 
                (:post1, 1), (:post1, 2), (:post1, 3), (:post2, 4), (:post2, 5), (:post2, 6), (:post3, 7), (:post3, 8), (:post3, 2)");
            $this->db->bind(':post1', $posts[0]->id);
            $this->db->bind(':post2', $posts[1]->id);
            $this->db->bind(':post3', $posts[2]->id);
            $this->db->execute();

            // Insert sample comments
            $this->db->query("INSERT INTO blog_comments (post_id, user_id, content, status, created_at) VALUES 
                (:post1, :userId, 'This was very helpful, thank you!', 'approved', '2025-04-12 09:45:22'),
                (:post1, :userId, 'I have a question about step 3. Can you provide more details?', 'approved', '2025-04-15 11:20:35'),
                (:post3, :userId, 'This is exactly what I needed. Thank you for the detailed explanation.', 'pending', '2025-04-23 14:55:10')");
            $this->db->bind(':post1', $posts[0]->id);
            $this->db->bind(':post3', $posts[2]->id);
            $this->db->bind(':userId', $user->id);
            $this->db->execute();

            // Get comment IDs
            $this->db->query("SELECT id FROM blog_comments ORDER BY id LIMIT 1");
            $comment = $this->db->single();

            if ($comment) {
                // Insert sample responses
                $this->db->query("INSERT INTO blog_comment_responses (comment_id, user_id, content, created_at) VALUES 
                    (:commentId, :userId, 'Glad you found it helpful!', '2025-04-12 10:30:15')");
                $this->db->bind(':commentId', $comment->id);
                $this->db->bind(':userId', $user->id);
                $this->db->execute();
            }
        }
    }

    /**
     * Update comment status
     * 
     * @param int $postId Post ID
     * @param int $commentId Comment ID
     * @param string $status New status
     * @return bool Success status
     */
    public function updateCommentStatus($postId, $commentId, $status)
    {
        try {
            // Verify that the comment belongs to the specified post
            $this->db->query("SELECT id FROM blog_comments WHERE id = :commentId AND post_id = :postId");
            $this->db->bind(':commentId', $commentId);
            $this->db->bind(':postId', $postId);
            $comment = $this->db->single();

            if (!$comment) {
                return false; // Comment not found or doesn't belong to the post
            }

            // Update the comment status
            $this->db->query("UPDATE blog_comments SET status = :status WHERE id = :commentId");
            $this->db->bind(':status', $status);
            $this->db->bind(':commentId', $commentId);

            return $this->db->execute();
        } catch (Exception $e) {
            error_log('Error updating comment status: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get support tickets data
     * 
     * @return array List of support tickets
     */
    public function getSupportTicketsData()
    {
        // Create support tickets tables if they don't exist
        $this->createSupportTicketsTablesIfNeeded();

        // Get all support tickets with user information
        $this->db->query("SELECT t.*, u.name as userName, u.email as userEmail 
                          FROM support_tickets t
                          LEFT JOIN users u ON t.user_id = u.id
                          ORDER BY 
                            CASE 
                                WHEN t.status = 'open' THEN 1
                                WHEN t.status = 'in-progress' THEN 2
                                ELSE 3
                            END,
                            CASE 
                                WHEN t.priority = 'critical' THEN 1
                                WHEN t.priority = 'high' THEN 2
                                WHEN t.priority = 'medium' THEN 3
                                ELSE 4
                            END,
                            t.created_at DESC");

        $tickets = $this->db->resultSet();
        $formattedTickets = [];

        if ($tickets) {
            foreach ($tickets as $ticket) {
                // Get responses for this ticket
                $this->db->query("SELECT r.*, 
                                CASE WHEN r.is_admin = 1 THEN 'Support Team' ELSE u.name END as respondentName
                                FROM support_replies r
                                LEFT JOIN users u ON r.user_id = u.id
                                WHERE r.ticket_id = :ticketId
                                ORDER BY r.created_at ASC");
                $this->db->bind(':ticketId', $ticket->id);
                $responses = $this->db->resultSet();

                $formattedResponses = [];
                if ($responses) {
                    foreach ($responses as $response) {
                        $formattedResponses[] = [
                            'id' => $response->id,
                            'ticketId' => $ticket->id,
                            'message' => $response->message,
                            'isStaff' => (bool)$response->is_admin,
                            'respondentName' => $response->respondentName,
                            'createdAt' => $response->created_at
                        ];
                    }
                }

                $formattedTickets[] = [
                    'id' => $ticket->id,
                    'subject' => $ticket->subject,
                    'description' => $ticket->description,
                    'userId' => $ticket->user_id,
                    'userName' => $ticket->userName,
                    'userEmail' => $ticket->userEmail,
                    'status' => $ticket->status,
                    'priority' => $ticket->priority,
                    'createdAt' => $ticket->created_at,
                    'updatedAt' => $ticket->updated_at,
                    'responses' => $formattedResponses
                ];
            }
        }

        return $formattedTickets;
    }

    /**
     * Create support tickets tables if they don't exist
     */
    private function createSupportTicketsTablesIfNeeded()
    {
        // Check if support_tickets table exists, if not create the necessary tables
        $this->db->query("SHOW TABLES LIKE 'support_tickets'");
        $tableExists = $this->db->resultSet();

        if (empty($tableExists)) {
            // Create activity_logs table first if it doesn't exist
            $this->db->query("SHOW TABLES LIKE 'activity_logs'");
            $logsExist = $this->db->resultSet();

            if (empty($logsExist)) {
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
            }

            // Create support_tickets table
            $this->db->query("CREATE TABLE support_tickets (
                id INT AUTO_INCREMENT PRIMARY KEY,
                subject VARCHAR(255) NOT NULL,
                description TEXT NOT NULL,
                user_id INT NOT NULL,
                status ENUM('open', 'in_progress', 'pending', 'resolved', 'closed', 'draft') DEFAULT 'open',
                priority ENUM('low', 'medium', 'high', 'critical') DEFAULT 'medium',
                category VARCHAR(100) DEFAULT NULL,
                assigned_to INT DEFAULT NULL,
                resolution TEXT DEFAULT NULL,
                attachment_filename VARCHAR(255) DEFAULT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                closed_at TIMESTAMP NULL DEFAULT NULL,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (assigned_to) REFERENCES users(id) ON DELETE SET NULL
            )");
            $this->db->execute();

            // Create support_replies table (renamed from ticket_responses)
            $this->db->query("CREATE TABLE support_replies (
                id INT AUTO_INCREMENT PRIMARY KEY,
                ticket_id INT NOT NULL,
                user_id INT,
                message TEXT NOT NULL,
                is_admin BOOLEAN DEFAULT FALSE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (ticket_id) REFERENCES support_tickets(id) ON DELETE CASCADE,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
            )");
            $this->db->execute();

            // Create faq_categories table
            $this->db->query("CREATE TABLE IF NOT EXISTS faq_categories (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL UNIQUE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )");
            $this->db->execute();

            // Create faqs table
            $this->db->query("CREATE TABLE IF NOT EXISTS faqs (
                id INT AUTO_INCREMENT PRIMARY KEY,
                question TEXT NOT NULL,
                answer TEXT NOT NULL,
                category VARCHAR(100) NOT NULL,
                display_order INT DEFAULT 0,
                is_published BOOLEAN DEFAULT TRUE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )");
            $this->db->execute();

            // Insert sample data
            $this->insertSampleTicketsData();
            $this->insertSampleFaqsData();
        }
    }

    /**
     * Insert sample tickets data
     */
    private function insertSampleTicketsData()
    {
        // First make sure we have at least one user for the sample tickets
        $this->db->query("SELECT id FROM users LIMIT 1");
        $user = $this->db->single();

        if (!$user) {
            // Create a sample user if no users exist
            $this->db->query("INSERT INTO users (name, email, password, account_type, terms_accepted) 
                             VALUES ('Sample User', 'user@example.com', :password, 'client', 1)");
            $this->db->bind(':password', password_hash('password123', PASSWORD_DEFAULT));
            $this->db->execute();

            $this->db->query("SELECT id FROM users LIMIT 1");
            $user = $this->db->single();
        }

        // Insert sample tickets
        $this->db->query("INSERT INTO support_tickets (subject, description, user_id, status, priority, created_at, updated_at) VALUES 
            ('Cannot access my account', 'I\'ve been trying to log in to my account for the past few days but keep getting an \"Invalid credentials\" error. I\'m sure my password is correct. Can you please help?', :userId, 'open', 'high', '2025-04-25 09:15:30', '2025-04-25 09:15:30'),
            ('Feature request: Dark mode', 'I would love to see a dark mode option added to the dashboard. It would be much easier on the eyes when working late at night. Is this something you\'re planning to implement in the future?', :userId, 'in-progress', 'medium', '2025-04-20 14:30:45', '2025-04-22 11:25:18'),
            ('Billing issue with my subscription', 'I was charged twice for my monthly subscription. The charges appeared on April 15 and April 16. Can you please refund one of these charges? My order numbers are #12345 and #12346.', :userId, 'resolved', 'critical', '2025-04-17 10:05:22', '2025-04-18 15:42:30')");
        $this->db->bind(':userId', $user->id);
        $this->db->execute();

        // Get ticket IDs
        $this->db->query("SELECT id FROM support_tickets ORDER BY id LIMIT 3");
        $tickets = $this->db->resultSet();

        if ($tickets && count($tickets) >= 3) {
            // Insert sample responses for the second ticket (in-progress)
            $this->db->query("INSERT INTO support_replies (ticket_id, user_id, message, is_admin, created_at) VALUES 
                (:ticketId, :userId, 'Thank you for your suggestion! We\'re actually working on a dark mode implementation right now. It should be available in our next update in about two weeks. I\'ll mark this ticket as in-progress and update you when it\'s released.', 1, '2025-04-22 11:25:18')");
            $this->db->bind(':ticketId', $tickets[1]->id);
            $this->db->bind(':userId', $user->id);
            $this->db->execute();

            // Insert sample responses for the third ticket (resolved)
            $this->db->query("INSERT INTO support_replies (ticket_id, user_id, message, is_admin, created_at) VALUES 
                (:ticketId, :userId, 'I\'ve checked our billing system and confirmed the duplicate charge. I\'ve processed a refund for order #12346, which should appear on your account in 3-5 business days. I sincerely apologize for the inconvenience.', 1, '2025-04-18 12:30:10'),
                (:ticketId, :userId, 'Thank you for the quick resolution! I\'ll keep an eye out for the refund.', 0, '2025-04-18 14:15:45'),
                (:ticketId, :userId, 'You\'re welcome! Is there anything else we can help you with?', 1, '2025-04-18 15:42:30')");
            $this->db->bind(':ticketId', $tickets[2]->id);
            $this->db->bind(':userId', $user->id);
            $this->db->execute();
        }

        // Insert sample activity logs
        $this->db->query("INSERT INTO activity_logs (user_id, action, module, target_id, ip_address, created_at) VALUES 
            (:userId, 'login', 'auth', NULL, '127.0.0.1', NOW() - INTERVAL 2 HOUR),
            (:userId, 'view', 'dashboard', NULL, '127.0.0.1', NOW() - INTERVAL 1 HOUR 55 MINUTE),
            (:userId, 'update', 'user', 2, '127.0.0.1', NOW() - INTERVAL 1 HOUR 30 MINUTE),
            (:userId, 'create', 'blog_post', 1, '127.0.0.1', NOW() - INTERVAL 50 MINUTE),
            (:userId, 'update', 'settings', NULL, '127.0.0.1', NOW() - INTERVAL 20 MINUTE)");
        $this->db->bind(':userId', $user->id);
        $this->db->execute();
    }

    /**
     * Insert sample FAQ data
     */
    private function insertSampleFaqsData()
    {
        // Insert sample FAQ categories
        $categories = ['General', 'Account', 'Billing', 'Technical Support'];

        foreach ($categories as $category) {
            $this->db->query("INSERT INTO faq_categories (name) VALUES (:name)");
            $this->db->bind(':name', $category);
            $this->db->execute();
        }

        // Sample FAQs
        $faqs = [
            [
                'question' => 'How do I reset my password?',
                'answer' => 'You can reset your password by clicking on the "Forgot Password" link on the login page. Follow the instructions sent to your email to create a new password.',
                'category' => 'Account',
                'display_order' => 1,
                'is_published' => 1
            ],
            [
                'question' => 'What payment methods do you accept?',
                'answer' => 'We accept credit cards (Visa, MasterCard, American Express), PayPal, and bank transfers for all our services.',
                'category' => 'Billing',
                'display_order' => 1,
                'is_published' => 1
            ],
            [
                'question' => 'How can I contact customer support?',
                'answer' => 'You can contact our customer support team by submitting a ticket through your dashboard, or by emailing support@example.com.',
                'category' => 'General',
                'display_order' => 1,
                'is_published' => 1
            ],
            [
                'question' => 'What are the system requirements?',
                'answer' => 'Our platform works best on modern browsers like Chrome, Firefox, Safari, and Edge. Make sure your browser is updated to the latest version for optimal performance.',
                'category' => 'Technical Support',
                'display_order' => 1,
                'is_published' => 1
            ],
            [
                'question' => 'How do I upgrade my subscription?',
                'answer' => 'To upgrade your subscription, go to your account settings and select "Subscription". From there, you can choose a new plan and complete the payment process.',
                'category' => 'Billing',
                'display_order' => 2,
                'is_published' => 1
            ]
        ];

        foreach ($faqs as $faq) {
            $this->db->query("INSERT INTO faqs (question, answer, category, display_order, is_published) 
                              VALUES (:question, :answer, :category, :display_order, :is_published)");
            $this->db->bind(':question', $faq['question']);
            $this->db->bind(':answer', $faq['answer']);
            $this->db->bind(':category', $faq['category']);
            $this->db->bind(':display_order', $faq['display_order']);
            $this->db->bind(':is_published', $faq['is_published']);
            $this->db->execute();
        }
    }

    /**
     * Add response to a support ticket
     * 
     * @param int $ticketId Ticket ID
     * @param string $response Response content
     * @return bool Success status
     */
    public function addTicketResponse($ticketId, $response)
    {
        try {
            // Verify that the ticket exists
            $this->db->query("SELECT id FROM support_tickets WHERE id = :ticketId");
            $this->db->bind(':ticketId', $ticketId);
            $ticket = $this->db->single();

            if (!$ticket) {
                return false; // Ticket not found
            }

            // Add the response
            $this->db->query("INSERT INTO support_replies (ticket_id, user_id, message, is_admin) 
                            VALUES (:ticketId, :userId, :message, :isStaff)");
            $this->db->bind(':ticketId', $ticketId);
            $this->db->bind(':userId', $_SESSION['user_id']); // Assuming the current logged-in user is the respondent
            $this->db->bind(':message', $response);
            $this->db->bind(':isStaff', isset($_SESSION['user_account_type']) && $_SESSION['user_account_type'] === 'admin' ? 1 : 0);

            return $this->db->execute();
        } catch (Exception $e) {
            error_log('Error adding ticket response: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Update support ticket
     * 
     * @param int $ticketId Ticket ID
     * @param array $updates Updates to apply
     * @return bool Success status
     */
    public function updateSupportTicket($ticketId, $updates)
    {
        try {
            // Update the support ticket
            $query = "UPDATE support_tickets SET ";
            $params = [];

            if (isset($updates['status'])) {
                $query .= "status = :status, ";
                $params[':status'] = $updates['status'];
            }

            if (isset($updates['priority'])) {
                $query .= "priority = :priority, ";
                $params[':priority'] = $updates['priority'];
            }

            $query = rtrim($query, ', ');
            $query .= " WHERE id = :ticketId";
            $params[':ticketId'] = $ticketId;

            $this->db->query($query);
            foreach ($params as $key => $value) {
                $this->db->bind($key, $value);
            }

            return $this->db->execute();
        } catch (Exception $e) {
            error_log('Error updating support ticket: ' . $e->getMessage());
            return false;
        }
    }
}
