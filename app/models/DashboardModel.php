<?php
/**
 * DashboardModel Class
 * Handles data for the dashboard panels
 */
class DashboardModel {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    /**
     * Get analytics data for the dashboard
     * 
     * @return array Analytics data
     */
    public function getAnalyticsData() {
        // Get total users count
        $this->db->query("SELECT COUNT(*) as total FROM users");
        $totalUsersResult = $this->db->single();
        $totalUsers = $totalUsersResult->total;
        
        // Get active users count (users who have logged in within last 30 days)
        $this->db->query("SELECT COUNT(*) as active FROM users WHERE updated_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)");
        $activeUsersResult = $this->db->single();
        $activeUsers = $activeUsersResult->active;
        
        // Get visit history for the last 7 days
        $visitHistory = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = date('Y-m-d', strtotime("-$i days"));
            
            // Query to count visits for this date (from activity_logs instead of admin_notifications)
            $this->db->query("SELECT COUNT(*) as visits FROM activity_logs WHERE action = 'visit' AND DATE(created_at) = :date");
            $this->db->bind(':date', $date);
            $visitResult = $this->db->single();
            
            $visits = $visitResult ? $visitResult->visits : 0;
            $visitHistory[] = [
                'date' => $date,
                'visits' => $visits
            ];
        }
        
        // Get today's visits
        $todayVisits = $visitHistory[6]['visits'];
        
        // Get yesterday's visits to calculate growth
        $yesterdayVisits = $visitHistory[5]['visits'];
        $visitsGrowth = $yesterdayVisits > 0 ? round((($todayVisits - $yesterdayVisits) / $yesterdayVisits) * 100) : 0;
        
        // Get user distribution by role
        $this->db->query("SELECT account_type as role, COUNT(*) as count FROM users GROUP BY account_type");
        $userDistributionResults = $this->db->resultSet();
        
        $userDistribution = [];
        foreach ($userDistributionResults as $result) {
            $userDistribution[] = [
                'role' => ucfirst($result->role),
                'count' => $result->count
            ];
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
     * @param array $filters Optional filters to apply (search, status, role)
     * @return array List of users
     */
    public function getUsersData($filters = []) {
        // Start building the query
        $sql = "SELECT id, name, email, account_type as role, 
                status, /* Use the actual status field from database instead of calculating it */
                updated_at as lastLogin, created_at as registeredDate
                FROM users WHERE 1=1";
        
        $params = [];
        
        // Apply search filter if provided
        if (isset($filters['search']) && !empty($filters['search'])) {
            $sql .= " AND (name LIKE :search OR email LIKE :search)";
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        // Apply status filter if provided
        if (isset($filters['status']) && !empty($filters['status'])) {
            $sql .= " AND status = :status";
            $params[':status'] = $filters['status'];
        }
        
        // Apply role filter if provided
        if (isset($filters['role']) && !empty($filters['role'])) {
            $sql .= " AND account_type = :role";
            $params[':role'] = strtolower($filters['role']); // Convert to lowercase for DB comparison
        }
        
        // Add order by
        $sql .= " ORDER BY id DESC";
        
        $this->db->query($sql);
        
        // Bind parameters
        foreach ($params as $param => $value) {
            $this->db->bind($param, $value);
        }
        
        $users = $this->db->resultSet();
        
        // Convert to array format expected by the frontend
        $formattedUsers = [];
        foreach ($users as $user) {
            $formattedUsers[] = [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => ucfirst($user->role),
                'status' => $user->status, // Use the actual status from database
                'lastLogin' => $user->lastLogin,
                'registeredDate' => $user->registeredDate
            ];
        }
        
        return $formattedUsers;
    }

    /**
     * Get user by ID
     * 
     * @param int $userId User ID
     * @return array|bool User data or false if not found
     */
    public function getUserById($userId) {
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
    public function updateUserStatus($userId, $status) {
        $this->db->query("UPDATE users SET status = :status WHERE id = :id");
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $userId);
        
        // Execute query
        if($this->db->execute()) {
            // Log the status change in the activity log
            $user = $this->getUserById($userId);
            $dashboardService = DashboardService::getInstance();
            $dashboardService->logActivity(
                'update', 
                'user_status',
                "Changed user status for {$user['email']} to {$status}"
            );
            return true;
        } else {
            return false;
        }
    }

    /**
     * Delete user
     * 
     * @param int $userId User ID
     * @return bool Success status
     */
    public function deleteUser($userId) {
        $this->db->query("DELETE FROM users WHERE id = :userId");
        $this->db->bind(':userId', $userId);
        return $this->db->execute();
    }

    /**
     * Get blog posts data
     * 
     * @return array List of blog posts
     */
    public function getBlogPostsData() {
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
            
            // Get tags for this post
            $this->db->query("SELECT t.name 
                             FROM blog_post_tags pt
                             JOIN blog_tags t ON pt.tag_id = t.id
                             WHERE pt.post_id = :postId");
            $this->db->bind(':postId', $post->id);
            $tagsResult = $this->db->resultSet();
            
            $tags = [];
            foreach ($tagsResult as $tag) {
                $tags[] = $tag->name;
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
        
        return $formattedPosts;
    }
    
    /**
     * Create new blog post
     * 
     * @param array $postData Post data
     * @return bool Success status
     */
    public function createBlogPost($postData) {
        // Start a transaction
        $this->db->beginTransaction();
        
        try {
            // Insert the blog post
            $this->db->query("INSERT INTO blog_posts (title, content, author_id, status, published_at) 
                             VALUES (:title, :content, :author_id, :status, :published_at)");
            
            $this->db->bind(':title', $postData['title']);
            $this->db->bind(':content', $postData['content']);
            $this->db->bind(':author_id', $postData['author_id']);
            $this->db->bind(':status', $postData['status']);
            
            // Set published_at to current time if status is published, otherwise NULL
            $publishedAt = ($postData['status'] === 'published') ? date('Y-m-d H:i:s') : null;
            $this->db->bind(':published_at', $publishedAt);
            
            $this->db->execute();
            $postId = $this->db->lastInsertId();
            
            // Add tags if provided
            if (isset($postData['tags']) && is_array($postData['tags']) && !empty($postData['tags'])) {
                foreach ($postData['tags'] as $tag) {
                    if (empty($tag)) continue;
                    
                    // Check if tag exists
                    $this->db->query("SELECT id FROM blog_tags WHERE name = :tagName");
                    $this->db->bind(':tagName', $tag);
                    $tagResult = $this->db->single();
                    
                    $tagId = 0;
                    if ($tagResult) {
                        $tagId = $tagResult->id;
                    } else {
                        // Create new tag
                        $this->db->query("INSERT INTO blog_tags (name) VALUES (:tagName)");
                        $this->db->bind(':tagName', $tag);
                        $this->db->execute();
                        $tagId = $this->db->lastInsertId();
                    }
                    
                    // Associate tag with post
                    $this->db->query("INSERT INTO blog_post_tags (post_id, tag_id) VALUES (:postId, :tagId)");
                    $this->db->bind(':postId', $postId);
                    $this->db->bind(':tagId', $tagId);
                    $this->db->execute();
                }
            }
            
            // Commit the transaction
            $this->db->endTransaction();
            return true;
        } catch (Exception $e) {
            // Rollback the transaction if something went wrong
            $this->db->cancelTransaction();
            error_log('Error creating blog post: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get blog post by ID
     * 
     * @param int $postId Post ID
     * @return array|bool Post data or false if not found
     */
    public function getBlogPostById($postId) {
        // Get the blog post
        $this->db->query("SELECT p.*, u.name as authorName 
                          FROM blog_posts p
                          LEFT JOIN users u ON p.author_id = u.id
                          WHERE p.id = :postId");
        $this->db->bind(':postId', $postId);
        $post = $this->db->single();
        
        if (!$post) {
            return false;
        }
        
        // Get comments for this post
        $this->db->query("SELECT c.*, u.name as userName 
                         FROM blog_comments c
                         LEFT JOIN users u ON c.user_id = u.id
                         WHERE c.post_id = :postId
                         ORDER BY c.created_at ASC");
        $this->db->bind(':postId', $postId);
        $comments = $this->db->resultSet();
        
        $formattedComments = [];
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
        
        // Get tags for this post
        $this->db->query("SELECT t.name 
                         FROM blog_post_tags pt
                         JOIN blog_tags t ON pt.tag_id = t.id
                         WHERE pt.post_id = :postId");
        $this->db->bind(':postId', $postId);
        $tagsResult = $this->db->resultSet();
        
        $tags = [];
        foreach ($tagsResult as $tag) {
            $tags[] = $tag->name;
        }
        
        return [
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
    
    /**
     * Update comment status
     * 
     * @param int $postId Post ID
     * @param int $commentId Comment ID
     * @param string $status New status
     * @return bool Success status
     */
    public function updateCommentStatus($postId, $commentId, $status) {
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
     * Create blog tables if they don't exist
     */
    private function createBlogTablesIfNeeded() {
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
    private function insertSampleBlogData() {
        // Insert sample blog posts
        $this->db->query("INSERT INTO blog_posts (title, content, author_id, status, published_at, created_at, updated_at) VALUES 
            ('Getting Started with Site Guardian', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua.', 1, 'published', '2025-04-10 10:30:00', '2025-04-05 14:22:10', '2025-04-10 10:30:00'),
            ('Advanced Security Features', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nisl eget ultricies tincidunt.', 1, 'draft', NULL, '2025-04-20 16:15:40', '2025-04-25 09:10:30'),
            ('Monitoring Your Website Performance', 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nullam auctor, nisl eget ultricies tincidunt.', 1, 'published', '2025-04-22 11:30:00', '2025-04-18 13:45:20', '2025-04-22 11:30:00')");
        $this->db->execute();
        
        // Insert sample tags
        $this->db->query("INSERT INTO blog_tags (name) VALUES 
            ('Getting Started'), ('Guide'), ('Tutorial'), ('Security'), ('Advanced'), ('Features'), ('Performance'), ('Monitoring')");
        $this->db->execute();
        
        // Associate tags with posts
        $this->db->query("INSERT INTO blog_post_tags (post_id, tag_id) VALUES 
            (1, 1), (1, 2), (1, 3), (2, 4), (2, 5), (2, 6), (3, 7), (3, 8), (3, 2)");
        $this->db->execute();
        
        // Insert sample comments
        $this->db->query("INSERT INTO blog_comments (post_id, user_id, content, status, created_at) VALUES 
            (1, 1, 'This was very helpful, thank you!', 'approved', '2025-04-12 09:45:22'),
            (1, 1, 'I have a question about step 3. Can you provide more details?', 'approved', '2025-04-15 11:20:35'),
            (3, 1, 'This is exactly what I needed. Thank you for the detailed explanation.', 'pending', '2025-04-23 14:55:10')");
        $this->db->execute();
        
        // Insert sample responses
        $this->db->query("INSERT INTO blog_comment_responses (comment_id, user_id, content, created_at) VALUES 
            (1, 1, 'Glad you found it helpful!', '2025-04-12 10:30:15')");
        $this->db->execute();
    }

    /**
     * Update blog post
     * 
     * @param int $postId Post ID
     * @param array $updates Updates to apply
     * @return bool Success status
     */
    public function updateBlogPost($postId, $updates) {
        // Start a transaction for complex updates
        $this->db->beginTransaction();
        
        try {
            // Update the blog post title and content if provided
            if (isset($updates['title']) || isset($updates['content']) || isset($updates['status'])) {
                $query = "UPDATE blog_posts SET ";
                $queryParts = [];
                $queryParams = [];
                
                if (isset($updates['title'])) {
                    $queryParts[] = "title = :title";
                    $queryParams[':title'] = $updates['title'];
                }
                
                if (isset($updates['content'])) {
                    $queryParts[] = "content = :content";
                    $queryParams[':content'] = $updates['content'];
                }
                
                if (isset($updates['status'])) {
                    $queryParts[] = "status = :status";
                    $queryParams[':status'] = $updates['status'];
                    
                    // If status is published and there's no published_at date, set it now
                    if ($updates['status'] === 'published') {
                        $queryParts[] = "published_at = CASE WHEN published_at IS NULL THEN NOW() ELSE published_at END";
                    }
                }
                
                $query .= implode(', ', $queryParts);
                $query .= " WHERE id = :postId";
                $queryParams[':postId'] = $postId;
                
                $this->db->query($query);
                
                foreach ($queryParams as $param => $value) {
                    $this->db->bind($param, $value);
                }
                
                $this->db->execute();
            }
            
            // Update tags if provided
            if (isset($updates['tags']) && is_array($updates['tags'])) {
                // First, remove all existing tag associations
                $this->db->query("DELETE FROM blog_post_tags WHERE post_id = :postId");
                $this->db->bind(':postId', $postId);
                $this->db->execute();
                
                // Then add new tags
                foreach ($updates['tags'] as $tag) {
                    // Check if tag exists
                    $this->db->query("SELECT id FROM blog_tags WHERE name = :tagName");
                    $this->db->bind(':tagName', $tag);
                    $tagResult = $this->db->single();
                    
                    $tagId = 0;
                    if ($tagResult) {
                        $tagId = $tagResult->id;
                    } else {
                        // Create new tag
                        $this->db->query("INSERT INTO blog_tags (name) VALUES (:tagName)");
                        $this->db->bind(':tagName', $tag);
                        $this->db->execute();
                        $tagId = $this->db->lastInsertId();
                    }
                    
                    // Associate tag with post
                    $this->db->query("INSERT INTO blog_post_tags (post_id, tag_id) VALUES (:postId, :tagId)");
                    $this->db->bind(':postId', $postId);
                    $this->db->bind(':tagId', $tagId);
                    $this->db->execute();
                }
            }
            
            // Update comments if provided
            if (isset($updates['comments']) && is_array($updates['comments'])) {
                foreach ($updates['comments'] as $commentUpdate) {
                    if (isset($commentUpdate['id']) && isset($commentUpdate['status'])) {
                        $this->db->query("UPDATE blog_comments SET 
                                        status = :status
                                        WHERE id = :commentId AND post_id = :postId");
                        $this->db->bind(':status', $commentUpdate['status']);
                        $this->db->bind(':commentId', $commentUpdate['id']);
                        $this->db->bind(':postId', $postId);
                        $this->db->execute();
                    }
                }
            }
            
            // Commit the transaction
            $this->db->endTransaction();
            return true;
        } catch (Exception $e) {
            // Rollback the transaction if something went wrong
            $this->db->cancelTransaction();
            error_log('Error updating blog post: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete blog post
     * 
     * @param int $postId Post ID
     * @return bool Success status
     */
    public function deleteBlogPost($postId) {
        try {
            // Due to foreign key constraints with CASCADE option,
            // deleting the post will also delete associated comments, responses, and tag relationships
            $this->db->query("DELETE FROM blog_posts WHERE id = :postId");
            $this->db->bind(':postId', $postId);
            return $this->db->execute();
        } catch (Exception $e) {
            error_log('Error deleting blog post: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Add response to a blog comment
     * 
     * @param int $postId Post ID
     * @param int $commentId Comment ID
     * @param string $response Response content
     * @return bool Success status
     */
    public function addCommentResponse($postId, $commentId, $response) {
        try {
            // Verify that the comment belongs to the specified post
            $this->db->query("SELECT id FROM blog_comments WHERE id = :commentId AND post_id = :postId");
            $this->db->bind(':commentId', $commentId);
            $this->db->bind(':postId', $postId);
            $comment = $this->db->single();
            
            if (!$comment) {
                return false; // Comment not found or doesn't belong to the post
            }
            
            // Add the response
            $this->db->query("INSERT INTO blog_comment_responses (comment_id, user_id, content) 
                            VALUES (:commentId, :userId, :content)");
            $this->db->bind(':commentId', $commentId);
            $this->db->bind(':userId', $_SESSION['user_id']); // Assuming the current logged-in user is the respondent
            $this->db->bind(':content', $response);
            
            return $this->db->execute();
        } catch (Exception $e) {
            error_log('Error adding comment response: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get support tickets data
     * 
     * @return array List of support tickets
     */
    /**
     * Get support tickets data for admin dashboard
     * 
     * @param int $page Current page number
     * @param int $perPage Items per page
     * @return array List of support tickets
     */
    public function getSupportTicketsData($page = 1, $perPage = 5) {
        // Create support tickets tables if they don't exist
        $this->createSupportTicketsTablesIfNeeded();
        
        // Calculate offset for pagination
        $offset = ($page - 1) * $perPage;
        
        // Get all support tickets with user information with pagination
        $this->db->query("SELECT t.*, u.name as user_name, u.email as userEmail 
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
                            t.created_at DESC
                          LIMIT :limit OFFSET :offset");
        
        $this->db->bind(':limit', $perPage);
        $this->db->bind(':offset', $offset);
        
        $tickets = $this->db->resultSet();
        $formattedTickets = [];
        
        foreach ($tickets as $ticket) {
            // Get responses for this ticket
            $this->db->query("SELECT r.*, 
                             CASE WHEN r.is_staff = 1 THEN 'Support Team' ELSE u.name END as respondentName
                             FROM ticket_responses r
                             LEFT JOIN users u ON r.user_id = u.id
                             WHERE r.ticket_id = :ticketId
                             ORDER BY r.created_at ASC");
            $this->db->bind(':ticketId', $ticket->id);
            $responses = $this->db->resultSet();
            
            $formattedResponses = [];
            foreach ($responses as $response) {
                $formattedResponses[] = [
                    'id' => $response->id,
                    'ticketId' => $ticket->id,
                    'message' => $response->message,
                    'isStaff' => (bool)$response->is_staff,
                    'respondentName' => $response->respondentName,
                    'created_at' => $response->created_at
                ];
            }
            
            $formattedTickets[] = [
                'id' => $ticket->id,
                'subject' => $ticket->subject,
                'description' => $ticket->description,
                'userId' => $ticket->user_id,
                'user_name' => $ticket->user_name,
                'userEmail' => $ticket->userEmail,
                'status' => $ticket->status,
                'priority' => $ticket->priority,
                'created_at' => $ticket->created_at,
                'updated_at' => $ticket->updated_at,
                'responses' => $formattedResponses
            ];
        }
        
        return $formattedTickets;
    }
    
    /**
     * Get total tickets count
     * 
     * @return int Total number of support tickets
     */
    public function getTotalTicketsCount() {
        $this->db->query("SELECT COUNT(*) as total FROM support_tickets");
        $result = $this->db->single();
        return $result->total;
    }
    
    /**
     * Get support ticket by ID
     *
     * @param int $ticketId Ticket ID
     * @return array|null Ticket data or null if not found
     */
    public function getSupportTicketById($ticketId) {
        // Create support tickets tables if they don't exist
        $this->createSupportTicketsTablesIfNeeded();
        
        // Get ticket with user information
        $this->db->query("SELECT t.*, u.name as user_name, u.email as userEmail 
                          FROM support_tickets t
                          LEFT JOIN users u ON t.user_id = u.id
                          WHERE t.id = :ticketId");
        $this->db->bind(':ticketId', $ticketId);
        $ticket = $this->db->single();
        
        if (!$ticket) {
            return null;
        }
        
        return [
            'id' => $ticket->id,
            'subject' => $ticket->subject,
            'description' => $ticket->description,
            'userId' => $ticket->user_id,
            'user_name' => $ticket->user_name,
            'userEmail' => $ticket->userEmail,
            'status' => $ticket->status,
            'priority' => $ticket->priority,
            'created_at' => $ticket->created_at,
            'updated_at' => $ticket->updated_at
        ];
    }
    
    /**
     * Get ticket responses by ticket ID
     *
     * @param int $ticketId Ticket ID
     * @return array List of responses
     */
    public function getTicketResponsesById($ticketId) {
        // Create support tickets tables if they don't exist
        $this->createSupportTicketsTablesIfNeeded();
        
        // Get responses for the ticket
        $this->db->query("SELECT r.*, 
                         CASE WHEN r.is_staff = 1 THEN 'Support Team' ELSE u.name END as respondentName
                         FROM ticket_responses r
                         LEFT JOIN users u ON r.user_id = u.id
                         WHERE r.ticket_id = :ticketId
                         ORDER BY r.created_at ASC");
        $this->db->bind(':ticketId', $ticketId);
        $responses = $this->db->resultSet();
        
        $formattedResponses = [];
        foreach ($responses as $response) {
            $formattedResponses[] = [
                'id' => $response->id,
                'ticketId' => $ticketId,
                'message' => $response->message,
                'isStaff' => (bool)$response->is_staff,
                'respondentName' => $response->respondentName,
                'created_at' => $response->created_at
            ];
        }
        
        return $formattedResponses;
    }
    
    /**
     * Create support tickets tables if they don't exist
     */
    private function createSupportTicketsTablesIfNeeded() {
        // Check if support_tickets table exists, if not create the necessary tables
        $this->db->query("SHOW TABLES LIKE 'support_tickets'");
        $tableExists = $this->db->resultSet();
        
        if (empty($tableExists)) {
            // Create support_tickets table
            $this->db->query("CREATE TABLE support_tickets (
                id INT AUTO_INCREMENT PRIMARY KEY,
                subject VARCHAR(255) NOT NULL,
                description TEXT NOT NULL,
                user_id INT NOT NULL,
                status ENUM('open', 'in-progress', 'resolved', 'closed') DEFAULT 'open',
                priority ENUM('low', 'medium', 'high', 'critical') DEFAULT 'medium',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )");
            $this->db->execute();
            
            // Create ticket_responses table
            $this->db->query("CREATE TABLE ticket_responses (
                id INT AUTO_INCREMENT PRIMARY KEY,
                ticket_id INT NOT NULL,
                user_id INT,
                message TEXT NOT NULL,
                is_staff BOOLEAN DEFAULT FALSE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                FOREIGN KEY (ticket_id) REFERENCES support_tickets(id) ON DELETE CASCADE,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
            )");
            $this->db->execute();
            
            // Insert sample data
            $this->insertSampleTicketsData();
        }
    }
    
    /**
     * Insert sample tickets data
     */
    private function insertSampleTicketsData() {
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
        
        // Insert sample responses for the second ticket (in-progress)
        $this->db->query("INSERT INTO ticket_responses (ticket_id, user_id, message, is_staff, created_at) VALUES 
            (2, :userId, 'Thank you for your suggestion! We\'re actually working on a dark mode implementation right now. It should be available in our next update in about two weeks. I\'ll mark this ticket as in-progress and update you when it\'s released.', 1, '2025-04-22 11:25:18')");
        $this->db->bind(':userId', $user->id);
        $this->db->execute();
        
        // Insert sample responses for the third ticket (resolved)
        $this->db->query("INSERT INTO ticket_responses (ticket_id, user_id, message, is_staff, created_at) VALUES 
            (3, :userId, 'I\'ve checked our billing system and confirmed the duplicate charge. I\'ve processed a refund for order #12346, which should appear on your account in 3-5 business days. I sincerely apologize for the inconvenience.', 1, '2025-04-18 12:30:10'),
            (3, :userId, 'Thank you for the quick resolution! I\'ll keep an eye out for the refund.', 0, '2025-04-18 14:15:45'),
            (3, :userId, 'You\'re welcome! Is there anything else we can help you with?', 1, '2025-04-18 15:42:30')");
        $this->db->bind(':userId', $user->id);
        $this->db->execute();
    }

    /**
     * Add response to a support ticket
     * 
     * @param int $ticketId Ticket ID
     * @param string $response Response content
     * @return bool Success status
     */
    /**
     * Add a response to a support ticket
     *
     * @param int $ticketId Ticket ID
     * @param string $response Response content
     * @return bool Success status
     */
    public function addTicketResponse($ticketId, $response) {
        try {
            // Verify that the ticket exists
            $this->db->query("SELECT id FROM support_tickets WHERE id = :ticketId");
            $this->db->bind(':ticketId', $ticketId);
            $ticket = $this->db->single();
            
            if (!$ticket) {
                return false; // Ticket not found
            }
            
            // Add the response
            $this->db->query("INSERT INTO ticket_responses (ticket_id, user_id, message, is_staff) 
                            VALUES (:ticketId, :userId, :message, :isStaff)");
            $this->db->bind(':ticketId', $ticketId);
            $this->db->bind(':userId', $_SESSION['user_id']); // Assuming the current logged-in user is the respondent
            $this->db->bind(':message', $response);
            $this->db->bind(':isStaff', $_SESSION['user_account_type'] === 'admin' ? 1 : 0); // Check if user is admin
            
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
    /**
     * Update a support ticket
     *
     * @param int $ticketId Ticket ID
     * @param array $updates Updates to apply
     * @return bool Success status
     */
    public function updateSupportTicket($ticketId, $updates) {
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
            
            // Always update the updated_at timestamp
            $query .= "updated_at = CURRENT_TIMESTAMP ";
            
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

    /**
     * Check if email is already in use by another user
     * 
     * @param string $email Email to check
     * @param int $excludeUserId User ID to exclude from check
     * @return bool True if email is in use
     */
    public function isEmailInUse($email, $excludeUserId) {
        $this->db->query("SELECT id FROM users WHERE email = :email AND id != :userId");
        $this->db->bind(':email', $email);
        $this->db->bind(':userId', $excludeUserId);
        return $this->db->rowCount() > 0;
    }

    /**
     * Verify user's password
     * 
     * @param int $userId User ID
     * @param string $password Password to verify
     * @return bool True if password is correct
     */
    public function verifyPassword($userId, $password) {
        $this->db->query("SELECT password FROM users WHERE id = :userId");
        $this->db->bind(':userId', $userId);
        $user = $this->db->single();
        
        if (!$user) {
            return false;
        }
        
        return password_verify($password, $user->password);
    }

    /**
     * Update user profile
     * 
     * @param array $data Profile data to update
     * @return bool Success status
     */
    public function updateProfile($data) {
        try {
            $query = "UPDATE users SET name = :name, email = :email";
            $params = [
                ':name' => $data['name'],
                ':email' => $data['email'],
                ':id' => $data['id']
            ];
            
            // Add password update if provided
            if (isset($data['password'])) {
                $query .= ", password = :password";
                $params[':password'] = $data['password'];
            }
            
            $query .= " WHERE id = :id";
            
            $this->db->query($query);
            foreach ($params as $key => $value) {
                $this->db->bind($key, $value);
            }
            
            return $this->db->execute();
        } catch (Exception $e) {
            error_log('Error updating profile: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get all resources
     * 
     * @param string $status Filter by status (active, inactive, or all)
     * @return array Resources
     */
    public function getResources($status = 'all') {
        $statusClause = ($status !== 'all') ? 'WHERE status = :status' : '';
        
        $this->db->query("SELECT * FROM resources $statusClause ORDER BY created_at DESC");
        
        if ($status !== 'all') {
            $this->db->bind(':status', $status);
        }
        
        return $this->db->resultSet();
    }
    
    /**
     * Get a single resource by ID
     * 
     * @param int $id Resource ID
     * @return object Resource or false if not found
     */
    public function getResourceById($id) {
        $this->db->query('SELECT * FROM resources WHERE id = :id');
        $this->db->bind(':id', $id);
        
        $resource = $this->db->single();
        
        if ($this->db->rowCount() > 0) {
            return $resource;
        } else {
            return false;
        }
    }
    
    /**
     * Add a new resource
     * 
     * @param array $data Resource data
     * @return bool True on success, false on failure
     */
    public function addResource($data) {
        // Extract YouTube ID and fetch data if not provided
        if (empty($data['youtube_id']) && !empty($data['youtube_url'])) {
            $youtubeData = $this->extractYoutubeData($data['youtube_url']);
            if ($youtubeData) {
                $data = array_merge($data, $youtubeData);
            } else {
                return false;
            }
        }
        
        $this->db->query('INSERT INTO resources (title, youtube_url, youtube_id, thumbnail_url, description, category, status) 
                        VALUES (:title, :youtube_url, :youtube_id, :thumbnail_url, :description, :category, :status)');
        
        // Bind values
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':youtube_url', $data['youtube_url']);
        $this->db->bind(':youtube_id', $data['youtube_id']);
        $this->db->bind(':thumbnail_url', $data['thumbnail_url']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':status', $data['status']);
        
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Update an existing resource
     * 
     * @param array $data Resource data
     * @return bool True on success, false on failure
     */
    public function updateResource($data) {
        // Extract YouTube ID and fetch data if YouTube URL has changed
        if (!empty($data['youtube_url']) && $this->hasYoutubeUrlChanged($data['id'], $data['youtube_url'])) {
            $youtubeData = $this->extractYoutubeData($data['youtube_url']);
            if ($youtubeData) {
                $data = array_merge($data, $youtubeData);
            }
        }
        
        $this->db->query('UPDATE resources 
                        SET title = :title, 
                            youtube_url = :youtube_url, 
                            youtube_id = :youtube_id,
                            thumbnail_url = :thumbnail_url, 
                            description = :description, 
                            category = :category, 
                            status = :status 
                        WHERE id = :id');
        
        // Bind values
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':youtube_url', $data['youtube_url']);
        $this->db->bind(':youtube_id', $data['youtube_id']);
        $this->db->bind(':thumbnail_url', $data['thumbnail_url']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':status', $data['status']);
        
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Delete a resource
     * 
     * @param int $id Resource ID
     * @return bool True on success, false on failure
     */
    public function deleteResource($id) {
        $this->db->query('DELETE FROM resources WHERE id = :id');
        $this->db->bind(':id', $id);
        
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Get resources by category
     * 
     * @param string $category Category name
     * @return array Resources
     */
    public function getResourcesByCategory($category) {
        $this->db->query('SELECT * FROM resources WHERE category = :category AND status = "active" ORDER BY created_at DESC');
        $this->db->bind(':category', $category);
        
        return $this->db->resultSet();
    }
    
    /**
     * Check if YouTube URL has changed
     * 
     * @param int $id Resource ID
     * @param string $youtubeUrl New YouTube URL
     * @return bool True if URL has changed, false otherwise
     */
    private function hasYoutubeUrlChanged($id, $youtubeUrl) {
        $this->db->query('SELECT youtube_url FROM resources WHERE id = :id');
        $this->db->bind(':id', $id);
        
        $resource = $this->db->single();
        
        if ($resource && $resource->youtube_url !== $youtubeUrl) {
            return true;
        }
        
        return false;
    }
    
    /**
     * Extract YouTube data from URL
     * 
     * @param string $youtubeUrl YouTube URL
     * @return array|bool Array of extracted data or false on failure
     */
    public function extractYoutubeData($youtubeUrl) {
        // Extract YouTube video ID from URL
        $videoId = $this->extractYoutubeId($youtubeUrl);
        
        if (!$videoId) {
            return false;
        }
        
        // Get video details using YouTube API or fallback to oEmbed
        $data = $this->getYoutubeVideoDetails($videoId);
        
        if (!$data) {
            return false;
        }
        
        return [
            'youtube_id' => $videoId,
            'title' => $data['title'],
            'thumbnail_url' => $data['thumbnail_url']
        ];
    }
    
    /**
     * Extract YouTube ID from URL
     * 
     * @param string $url YouTube URL
     * @return string|bool YouTube ID or false if not found
     */
    private function extractYoutubeId($url) {
        // Pattern to match YouTube URLs and extract video ID
        $pattern = '/(?:youtube\.com\/(?:[^\/\n\s]+\/\S+\/|(?:v|e(?:mbed)?)\/|\S*?[?&]v=)|youtu\.be\/)([a-zA-Z0-9_-]{11})/';
        
        if (preg_match($pattern, $url, $matches)) {
            return $matches[1];
        }
        
        return false;
    }
    
    /**
     * Get YouTube video details using oEmbed
     * 
     * @param string $videoId YouTube video ID
     * @return array|bool Video details or false on failure
     */
    private function getYoutubeVideoDetails($videoId) {
        $oembedUrl = "https://www.youtube.com/oembed?url=https://www.youtube.com/watch?v={$videoId}&format=json";
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $oembedUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            error_log('cURL Error: ' . curl_error($ch));
            curl_close($ch);
            return false;
        }
        
        curl_close($ch);
        
        $data = json_decode($response, true);
        
        if (!$data || !isset($data['title'])) {
            return false;
        }
        
        // If thumbnail_url is not in oEmbed response, use default YouTube thumbnail
        if (!isset($data['thumbnail_url'])) {
            $data['thumbnail_url'] = "https://img.youtube.com/vi/{$videoId}/maxresdefault.jpg";
        }
        
        return $data;
    }

    /**
     * Get all events
     * 
     * @param string $status Filter by status (draft, published, canceled, completed or all)
     * @return array Events
     */
    public function getEvents($status = 'all') {
        $statusClause = ($status !== 'all') ? 'WHERE status = :status' : '';
        
        $this->db->query("SELECT e.*, u.name as creator_name
                         FROM events e
                         LEFT JOIN users u ON e.created_by = u.id
                         $statusClause 
                         ORDER BY e.start_date DESC");
        
        if ($status !== 'all') {
            $this->db->bind(':status', $status);
        }
        
        return $this->db->resultSet();
    }
    
    /**
     * Get a single event by ID
     * 
     * @param int $id Event ID
     * @return object Event or false if not found
     */
    public function getEventById($id) {
        $this->db->query('SELECT e.*, u.name as creator_name 
                         FROM events e
                         LEFT JOIN users u ON e.created_by = u.id
                         WHERE e.id = :id');
        $this->db->bind(':id', $id);
        
        $event = $this->db->single();
        
        if ($this->db->rowCount() > 0) {
            return $event;
        } else {
            return false;
        }
    }
    
    /**
     * Add a new event
     * 
     * @param array $data Event data
     * @return bool True on success, false on failure
     */
    public function addEvent($data) {
        $this->db->query('INSERT INTO events (
                          title, 
                          description, 
                          event_type,
                          category,
                          start_date, 
                          end_date,
                          location, 
                          is_virtual,
                          virtual_link,
                          max_attendees,
                          image,
                          status,
                          created_by
                        ) VALUES (
                          :title, 
                          :description, 
                          :event_type,
                          :category,
                          :start_date, 
                          :end_date,
                          :location, 
                          :is_virtual,
                          :virtual_link,
                          :max_attendees,
                          :image,
                          :status,
                          :created_by
                        )');
        
        // Bind values
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':event_type', $data['event_type'] ?? 'workshop');
        $this->db->bind(':category', $data['category'] ?? 'general');
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date']);
        $this->db->bind(':location', $data['location']);
        $this->db->bind(':is_virtual', $data['is_virtual'] ?? 0);
        $this->db->bind(':virtual_link', $data['virtual_link'] ?? null);
        $this->db->bind(':max_attendees', $data['max_attendees'] ?? null);
        $this->db->bind(':image', $data['image'] ?? null);
        $this->db->bind(':status', $data['status'] ?? 'draft');
        $this->db->bind(':created_by', $data['created_by']);
        
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Update an existing event
     * 
     * @param array $data Event data
     * @return bool True on success, false on failure
     */
    public function updateEvent($data) {
        $this->db->query('UPDATE events SET 
                        title = :title, 
                        description = :description, 
                        event_type = :event_type,
                        category = :category,
                        start_date = :start_date,
                        end_date = :end_date,
                        location = :location, 
                        is_virtual = :is_virtual,
                        virtual_link = :virtual_link,
                        max_attendees = :max_attendees,
                        image = :image,
                        status = :status
                      WHERE id = :id');
        
        // Bind values
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':event_type', $data['event_type'] ?? 'workshop');
        $this->db->bind(':category', $data['category'] ?? 'general');
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date']);
        $this->db->bind(':location', $data['location']);
        $this->db->bind(':is_virtual', $data['is_virtual'] ?? 0);
        $this->db->bind(':virtual_link', $data['virtual_link'] ?? null);
        $this->db->bind(':max_attendees', $data['max_attendees'] ?? null);
        $this->db->bind(':image', $data['image'] ?? null);
        $this->db->bind(':status', $data['status']);
        
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Delete an event
     * 
     * @param int $id Event ID
     * @return bool True on success, false on failure
     */
    public function deleteEvent($id) {
        $this->db->query('DELETE FROM events WHERE id = :id');
        $this->db->bind(':id', $id);
        
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Get events by date range
     * 
     * @param string $startDate Start date in Y-m-d format
     * @param string $endDate End date in Y-m-d format
     * @return array Events in the date range
     */
    public function getEventsByDateRange($startDate, $endDate) {
        $this->db->query('SELECT * FROM events 
                         WHERE start_date BETWEEN :start_date AND :end_date 
                         AND status = "published" 
                         ORDER BY start_date');
        $this->db->bind(':start_date', $startDate);
        $this->db->bind(':end_date', $endDate);
        
        return $this->db->resultSet();
    }
    
    /**
     * Get upcoming events
     * 
     * @param int $limit Number of events to return
     * @return array Upcoming events
     */
    public function getUpcomingEvents($limit = 5) {
        $this->db->query('SELECT * FROM events 
                         WHERE start_date >= CURDATE() 
                         AND status = "published" 
                         ORDER BY start_date 
                         LIMIT :limit');
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }
    
    /**
     * Get events by type
     * 
     * @param string $type Event type
     * @return array Events of the specified type
     */
    public function getEventsByType($type) {
        $this->db->query('SELECT * FROM events 
                         WHERE event_type = :type 
                         AND status = "published" 
                         ORDER BY start_date');
        $this->db->bind(':type', $type);
        
        return $this->db->resultSet();
    }
    
    /**
     * Get events by category
     * 
     * @param string $category Event category
     * @return array Events in the specified category
     */
    public function getEventsByCategory($category) {
        $this->db->query('SELECT * FROM events 
                         WHERE category = :category 
                         AND status = "published" 
                         ORDER BY start_date');
        $this->db->bind(':category', $category);
        
        return $this->db->resultSet();
    }

    /**
     * Get all projects
     * 
     * @param string $status Filter by status (active, draft, completed, canceled or all)
     * @return array Projects
     */
    public function getProjects($status = 'all') {
        $statusClause = ($status !== 'all') ? 'WHERE status = :status' : '';
        
        $this->db->query("SELECT p.*, u.name as creator_name
                         FROM projects p
                         LEFT JOIN users u ON p.created_by = u.id
                         $statusClause 
                         ORDER BY p.start_date DESC");
        
        if ($status !== 'all') {
            $this->db->bind(':status', $status);
        }
        
        return $this->db->resultSet();
    }
    
    /**
     * Get a single project by ID
     * 
     * @param int $id Project ID
     * @return object Project or false if not found
     */
    public function getProjectById($id) {
        $this->db->query('SELECT p.*, u.name as creator_name 
                         FROM projects p
                         LEFT JOIN users u ON p.created_by = u.id
                         WHERE p.id = :id');
        $this->db->bind(':id', $id);
        
        $project = $this->db->single();
        
        if ($this->db->rowCount() > 0) {
            return $project;
        } else {
            return false;
        }
    }
    
    /**
     * Add a new project
     * 
     * @param array $data Project data
     * @return bool True on success, false on failure
     */
    public function addProject($data) {
        $this->db->query('INSERT INTO projects (
                          title, 
                          description, 
                          category,
                          start_date, 
                          end_date,
                          location, 
                          is_remote,
                          max_participants,
                          skills_required,
                          image,
                          status,
                          created_by
                        ) VALUES (
                          :title, 
                          :description, 
                          :category,
                          :start_date, 
                          :end_date,
                          :location, 
                          :is_remote,
                          :max_participants,
                          :skills_required,
                          :image,
                          :status,
                          :created_by
                        )');
        
        // Bind values
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date']);
        $this->db->bind(':location', $data['location']);
        $this->db->bind(':is_remote', $data['is_remote']);
        $this->db->bind(':max_participants', $data['max_participants']);
        $this->db->bind(':skills_required', $data['skills_required']);
        $this->db->bind(':image', $data['image']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':created_by', $data['created_by']);
        
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Update an existing project
     * 
     * @param array $data Project data
     * @return bool True on success, false on failure
     */
    public function updateProject($data) {
        $this->db->query('UPDATE projects SET 
                        title = :title, 
                        description = :description, 
                        category = :category,
                        start_date = :start_date,
                        end_date = :end_date,
                        location = :location, 
                        is_remote = :is_remote,
                        max_participants = :max_participants,
                        skills_required = :skills_required,
                        image = :image,
                        status = :status
                      WHERE id = :id');
        
        // Bind values
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date']);
        $this->db->bind(':location', $data['location']);
        $this->db->bind(':is_remote', $data['is_remote']);
        $this->db->bind(':max_participants', $data['max_participants']);
        $this->db->bind(':skills_required', $data['skills_required']);
        $this->db->bind(':image', $data['image']);
        $this->db->bind(':status', $data['status']);
        
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Delete a project
     * 
     * @param int $id Project ID
     * @return bool True on success, false on failure
     */
    public function deleteProject($id) {
        // First delete all candidatures associated with this project
        $this->db->query('DELETE FROM project_candidatures WHERE project_id = :project_id');
        $this->db->bind(':project_id', $id);
        $this->db->execute();
        
        // Then delete the project
        $this->db->query('DELETE FROM projects WHERE id = :id');
        $this->db->bind(':id', $id);
        
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Get candidatures for a specific project
     * 
     * @param int $projectId Project ID
     * @return array Candidatures for the project
     */
    public function getProjectCandidatures($projectId) {
        $this->db->query('SELECT c.*, u.name as user_name 
                         FROM project_candidatures c
                         LEFT JOIN users u ON c.user_id = u.id
                         WHERE c.project_id = :project_id');
        $this->db->bind(':project_id', $projectId);
        
        return $this->db->resultSet();
    }
    
    /**
     * Update candidature status
     * 
     * @param int $candidatureId Candidature ID
     * @param string $status New status
     * @return bool True on success, false on failure
     */
    public function updateCandidatureStatus($candidatureId, $status) {
        $this->db->query('UPDATE project_candidatures SET status = :status WHERE id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $candidatureId);
        
        return $this->db->execute();
    }

    /**
     * Check if a user has a candidature for a project
     * 
     * @param int $projectId Project ID
     * @param int $userId User ID
     * @return bool True if user has a candidature, false otherwise
     */
    public function hasUserCandidature($projectId, $userId) {
        $this->db->query('SELECT id FROM project_candidatures 
                         WHERE project_id = :project_id AND user_id = :user_id');
        $this->db->bind(':project_id', $projectId);
        $this->db->bind(':user_id', $userId);
        
        $this->db->single();
        
        return $this->db->rowCount() > 0;
    }
    
    /**
     * Add a candidature for a project
     * 
     * @param array $data Candidature data
     * @return bool True on success, false on failure
     */
    public function addCandidature($data) {
        $this->db->query('INSERT INTO project_candidatures (
                          project_id,
                          user_id,
                          message,
                          skills,
                          status
                        ) VALUES (
                          :project_id,
                          :user_id,
                          :message,
                          :skills,
                          :status
                        )');
        
        $this->db->bind(':project_id', $data['project_id']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':message', $data['message']);
        $this->db->bind(':skills', $data['skills']);
        $this->db->bind(':status', $data['status'] ?? 'pending');
        
        return $this->db->execute();
    }

    /**
     * Get projects by category
     * 
     * @param string $category Project category
     * @return array Projects in the specified category
     */
    public function getProjectsByCategory($category) {
        $this->db->query('SELECT * FROM projects 
                         WHERE category = :category 
                         AND status = "active"
                         ORDER BY start_date');
        $this->db->bind(':category', $category);
        
        return $this->db->resultSet();
    }
    
    /**
     * Get active/published projects
     * 
     * @param int $limit Number of projects to return
     * @return array Active projects
     */
    public function getActiveProjects($limit = 0) {
        $limitClause = $limit > 0 ? 'LIMIT :limit' : '';
        
        $this->db->query("SELECT * FROM projects 
                         WHERE status = 'active' 
                         ORDER BY start_date 
                         $limitClause");
        
        if ($limit > 0) {
            $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        }
        
        return $this->db->resultSet();
    }
}