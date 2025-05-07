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
            
            // Query to count visits for this date (from admin_tasks or using a dedicated visits table if available)
            $this->db->query("SELECT COUNT(*) as visits FROM admin_notifications WHERE DATE(created_at) = :date");
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
     * @return array List of users
     */
    public function getUsersData() {
        $this->db->query("SELECT id, name, email, account_type as role, 
                          IF(updated_at >= DATE_SUB(NOW(), INTERVAL 30 DAY), 'Active', 'Inactive') as status,
                          updated_at as lastLogin, created_at as registeredDate
                          FROM users
                          ORDER BY id DESC");
        
        $users = $this->db->resultSet();
        
        // Convert to array format expected by the frontend
        $formattedUsers = [];
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
    public function getSupportTicketsData() {
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
                    'createdAt' => $response->created_at
                ];
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
        
        return $formattedTickets;
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
            $this->db->bind(':isStaff', $_SESSION['is_staff'] ? 1 : 0); // Assuming a session variable to check if the user is staff
            
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