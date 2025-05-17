<?php
class Forum {
    private $db;
    
    public function __construct() {
        $this->db = new Database;
    }
    
    /**
     * Get all forum categories
     * 
     * @return array Categories
     */
    public function getCategories() {
        $this->db->query('SELECT * FROM forum_categories WHERE status = "active" ORDER BY display_order, name');
        return $this->db->resultSet();
    }
    
    /**
     * Get a single category by slug
     * 
     * @param string $slug Category slug
     * @return object Category
     */
    public function getCategoryBySlug($slug) {
        $this->db->query('SELECT * FROM forum_categories WHERE slug = :slug AND status = "active"');
        $this->db->bind(':slug', $slug);
        return $this->db->single();
    }
    
    /**
     * Get a single category by ID
     * 
     * @param int $id Category ID
     * @return object Category
     */
    public function getCategoryById($id) {
        $this->db->query('SELECT * FROM forum_categories WHERE id = :id AND status = "active"');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    
    /**
     * Get featured threads (pinned)
     * 
     * @param int $limit Number of threads to return
     * @return array Threads
     */
    public function getFeaturedThreads($limit = 5) {
        $this->db->query('SELECT t.*, c.name as category_name, c.slug as category_slug, 
                        u.name as author_name, u.profile_image as author_avatar,
                        (SELECT COUNT(*) FROM forum_replies WHERE thread_id = t.id AND status = "active") as reply_count
                        FROM forum_threads t
                        JOIN forum_categories c ON t.category_id = c.id
                        JOIN users u ON t.user_id = u.id
                        WHERE t.is_pinned = TRUE AND t.status = "active" AND c.status = "active"
                        ORDER BY t.created_at DESC
                        LIMIT :limit');
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }
    
    /**
     * Get recent threads
     * 
     * @param int $limit Number of threads to return
     * @param array $excludeIds IDs to exclude (e.g., featured threads)
     * @return array Threads
     */
    public function getRecentThreads($limit = 10, $excludeIds = []) {
        $excludeClause = '';
        
        if (!empty($excludeIds)) {
            $excludeClause = 'AND t.id NOT IN (' . implode(',', array_map('intval', $excludeIds)) . ')';
        }
        
        $this->db->query("SELECT t.*, c.name as category_name, c.slug as category_slug, 
                        u.name as author_name, u.profile_image as author_avatar,
                        (SELECT COUNT(*) FROM forum_replies WHERE thread_id = t.id AND status = 'active') as reply_count
                        FROM forum_threads t
                        JOIN forum_categories c ON t.category_id = c.id
                        JOIN users u ON t.user_id = u.id
                        WHERE t.status = 'active' AND c.status = 'active' $excludeClause
                        ORDER BY t.created_at DESC
                        LIMIT :limit");
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }
    
    /**
     * Get threads by category
     * 
     * @param int $categoryId Category ID
     * @param int $limit Number of threads to return
     * @param int $offset Offset for pagination
     * @return array Threads
     */
    public function getThreadsByCategory($categoryId, $limit = 20, $offset = 0) {
        $this->db->query('SELECT t.*, u.name as author_name, u.profile_image as author_avatar,
                        (SELECT COUNT(*) FROM forum_replies WHERE thread_id = t.id AND status = "active") as reply_count
                        FROM forum_threads t
                        JOIN users u ON t.user_id = u.id
                        WHERE t.category_id = :category_id AND t.status = "active" 
                        ORDER BY t.is_pinned DESC, t.created_at DESC
                        LIMIT :limit OFFSET :offset');
        $this->db->bind(':category_id', $categoryId);
        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);
        return $this->db->resultSet();
    }
    
    /**
     * Count threads in a category
     * 
     * @param int $categoryId Category ID
     * @return int Thread count
     */
    public function countThreadsByCategory($categoryId) {
        $this->db->query('SELECT COUNT(*) as count FROM forum_threads 
                        WHERE category_id = :category_id AND status = "active"');
        $this->db->bind(':category_id', $categoryId);
        $result = $this->db->single();
        return $result->count;
    }
    
    /**
     * Get a single thread by ID
     * 
     * @param int $id Thread ID
     * @return object Thread
     */
    public function getThreadById($id) {
        $this->db->query('SELECT t.*, c.name as category_name, c.slug as category_slug, 
                        u.name as author_name, u.profile_image as author_avatar
                        FROM forum_threads t
                        JOIN forum_categories c ON t.category_id = c.id
                        JOIN users u ON t.user_id = u.id
                        WHERE t.id = :id AND t.status = "active"');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    
    /**
     * Get a single thread by slug
     * 
     * @param string $slug Thread slug
     * @return object Thread
     */
    public function getThreadBySlug($slug) {
        $this->db->query('SELECT t.*, c.name as category_name, c.slug as category_slug, 
                        u.name as author_name, u.profile_image as author_avatar
                        FROM forum_threads t
                        JOIN forum_categories c ON t.category_id = c.id
                        JOIN users u ON t.user_id = u.id
                        WHERE t.slug = :slug AND t.status = "active"');
        $this->db->bind(':slug', $slug);
        return $this->db->single();
    }
    
    /**
     * Increment thread view count
     * 
     * @param int $id Thread ID
     * @return bool True if successful
     */
    public function incrementViewCount($id) {
        $this->db->query('UPDATE forum_threads SET view_count = view_count + 1 WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }
    
    /**
     * Get replies for a thread
     * 
     * @param int $threadId Thread ID
     * @param int $limit Number of replies to return
     * @param int $offset Offset for pagination
     * @return array Replies
     */
    public function getRepliesByThread($threadId, $limit = 50, $offset = 0) {
        $this->db->query('SELECT r.*, u.name as author_name, u.profile_image as author_avatar,
                        (SELECT COUNT(*) FROM forum_votes WHERE content_type = "reply" AND content_id = r.id AND vote_type = "up") as upvotes,
                        (SELECT COUNT(*) FROM forum_votes WHERE content_type = "reply" AND content_id = r.id AND vote_type = "down") as downvotes
                        FROM forum_replies r
                        JOIN users u ON r.user_id = u.id
                        WHERE r.thread_id = :thread_id AND r.status = "active" 
                        ORDER BY r.is_solution DESC, r.created_at ASC
                        LIMIT :limit OFFSET :offset');
        $this->db->bind(':thread_id', $threadId);
        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);
        return $this->db->resultSet();
    }
    
    /**
     * Count replies in a thread
     * 
     * @param int $threadId Thread ID
     * @return int Reply count
     */
    public function countRepliesByThread($threadId) {
        $this->db->query('SELECT COUNT(*) as count FROM forum_replies 
                         WHERE thread_id = :thread_id AND status = "active"');
        $this->db->bind(':thread_id', $threadId);
        $result = $this->db->single();
        return $result->count;
    }
    
    /**
     * Search threads
     * 
     * @param string $query Search query
     * @param int $limit Number of threads to return
     * @param int $offset Offset for pagination
     * @return array Threads
     */
    public function searchThreads($query, $limit = 20, $offset = 0) {
        $searchTerm = '%' . $query . '%';
        
        $this->db->query('SELECT t.*, c.name as category_name, c.slug as category_slug, 
                        u.name as author_name, u.profile_image as author_avatar,
                        (SELECT COUNT(*) FROM forum_replies WHERE thread_id = t.id AND status = "active") as reply_count
                        FROM forum_threads t
                        JOIN forum_categories c ON t.category_id = c.id
                        JOIN users u ON t.user_id = u.id
                        WHERE (t.title LIKE :search OR t.content LIKE :search2) 
                        AND t.status = "active" AND c.status = "active"
                        ORDER BY t.created_at DESC
                        LIMIT :limit OFFSET :offset');
        $this->db->bind(':search', $searchTerm);
        $this->db->bind(':search2', $searchTerm);
        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);
        return $this->db->resultSet();
    }
    
    /**
     * Create a new thread
     * 
     * @param array $data Thread data
     * @return int|bool Thread ID if successful, false otherwise
     */
    public function createThread($data) {
        // Generate slug from title
        $slug = $this->createSlug($data['title']);
        
        $this->db->query('INSERT INTO forum_threads (category_id, user_id, title, content, slug) 
                        VALUES (:category_id, :user_id, :title, :content, :slug)');
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':content', $data['content']);
        $this->db->bind(':slug', $slug);
        
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }
    
    /**
     * Create a new reply
     * 
     * @param array $data Reply data
     * @return int|bool Reply ID if successful, false otherwise
     */
    public function createReply($data) {
        try {
            // Validate input data
            if (!isset($data['thread_id']) || empty($data['thread_id'])) {
                error_log('Forum::createReply - Missing thread_id');
                return false;
            }
            
            if (!isset($data['user_id']) || empty($data['user_id'])) {
                error_log('Forum::createReply - Missing user_id');
                return false;
            }
            
            if (!isset($data['content']) || empty($data['content'])) {
                error_log('Forum::createReply - Missing content');
                return false;
            }
            
            // Insert the reply
            $this->db->query('INSERT INTO forum_replies (thread_id, user_id, content) 
                            VALUES (:thread_id, :user_id, :content)');
            $this->db->bind(':thread_id', $data['thread_id']);
            $this->db->bind(':user_id', $data['user_id']);
            $this->db->bind(':content', $data['content']);
            
            $result = $this->db->execute();
            
            if (!$result) {
                error_log('Forum::createReply - Execute failed');
                return false;
            }
            
            // Get the new reply ID
            $replyId = $this->db->lastInsertId();
            
            if (!$replyId) {
                error_log('Forum::createReply - Failed to get lastInsertId');
                return false;
            }
            
            // Update thread's updated_at timestamp
            $this->db->query('UPDATE forum_threads SET updated_at = CURRENT_TIMESTAMP WHERE id = :thread_id');
            $this->db->bind(':thread_id', $data['thread_id']);
            $this->db->execute();
            
            return $replyId;
        } catch (Exception $e) {
            error_log('Forum::createReply - Exception: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Vote on thread or reply
     * 
     * @param array $data Vote data
     * @return bool True if successful
     */
    public function vote($data) {
        // Check if user has already voted
        $this->db->query('SELECT * FROM forum_votes 
                        WHERE user_id = :user_id AND content_type = :content_type AND content_id = :content_id');
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':content_type', $data['content_type']);
        $this->db->bind(':content_id', $data['content_id']);
        
        $existingVote = $this->db->single();
        
        if ($existingVote) {
            // Update existing vote if different
            if ($existingVote->vote_type != $data['vote_type']) {
                $this->db->query('UPDATE forum_votes SET vote_type = :vote_type WHERE id = :id');
                $this->db->bind(':vote_type', $data['vote_type']);
                $this->db->bind(':id', $existingVote->id);
                return $this->db->execute();
            }
            
            // Remove vote if same (toggle)
            $this->db->query('DELETE FROM forum_votes WHERE id = :id');
            $this->db->bind(':id', $existingVote->id);
            return $this->db->execute();
        }
        
        // Create new vote
        $this->db->query('INSERT INTO forum_votes (user_id, content_type, content_id, vote_type) 
                        VALUES (:user_id, :content_type, :content_id, :vote_type)');
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':content_type', $data['content_type']);
        $this->db->bind(':content_id', $data['content_id']);
        $this->db->bind(':vote_type', $data['vote_type']);
        
        return $this->db->execute();
    }
    
    /**
     * Get user's vote on a thread or reply
     * 
     * @param int $userId User ID
     * @param string $contentType Content type (thread or reply)
     * @param int $contentId Content ID
     * @return string|null Vote type (up or down) or null if not voted
     */
    public function getUserVote($userId, $contentType, $contentId) {
        $this->db->query('SELECT vote_type FROM forum_votes 
                        WHERE user_id = :user_id AND content_type = :content_type AND content_id = :content_id');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':content_type', $contentType);
        $this->db->bind(':content_id', $contentId);
        
        $result = $this->db->single();
        
        return $result ? $result->vote_type : null;
    }
    
    /**
     * Mark a reply as a solution
     * 
     * @param int $replyId Reply ID
     * @param int $threadId Thread ID (for verification)
     * @return bool True if successful
     */
    public function markAsSolution($replyId, $threadId) {
        // First, unmark any existing solution
        $this->db->query('UPDATE forum_replies SET is_solution = FALSE 
                        WHERE thread_id = :thread_id AND is_solution = TRUE');
        $this->db->bind(':thread_id', $threadId);
        $this->db->execute();
        
        // Mark the new solution
        $this->db->query('UPDATE forum_replies SET is_solution = TRUE 
                        WHERE id = :reply_id AND thread_id = :thread_id');
        $this->db->bind(':reply_id', $replyId);
        $this->db->bind(':thread_id', $threadId);
        
        return $this->db->execute();
    }
    
    /**
     * Helper function to create a slug from a string
     * 
     * @param string $text Text to create slug from
     * @return string Slug
     */
    private function createSlug($text) {
        // Convert to lowercase and remove unwanted characters
        $slug = strtolower(trim(preg_replace('/[^a-zA-Z0-9-]+/', '-', $text), '-'));
        
        // Check if slug already exists
        $this->db->query('SELECT COUNT(*) as count FROM forum_threads WHERE slug = :slug');
        $this->db->bind(':slug', $slug);
        $result = $this->db->single();
        
        // If slug exists, append a number
        if ($result->count > 0) {
            $baseSlug = $slug;
            $i = 1;
            
            do {
                $slug = $baseSlug . '-' . $i++;
                $this->db->query('SELECT COUNT(*) as count FROM forum_threads WHERE slug = :slug');
                $this->db->bind(':slug', $slug);
                $result = $this->db->single();
            } while ($result->count > 0);
        }
        
        return $slug;
    }
    
    /**
     * Get a single reply by ID
     * 
     * @param int $id Reply ID
     * @return object Reply
     */
    public function getReplyById($id) {
        $this->db->query('SELECT r.*, u.name as author_name, u.profile_image as author_avatar
                         FROM forum_replies r
                         JOIN users u ON r.user_id = u.id
                         WHERE r.id = :id AND r.status = "active"');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    
    /**
     * Update a reply
     * 
     * @param array $data Reply data
     * @return bool True if successful
     */
    public function updateReply($data) {
        try {
            // Update the reply
            $this->db->query('UPDATE forum_replies SET content = :content, updated_at = NOW() 
                            WHERE id = :id AND status = "active"');
            $this->db->bind(':content', $data['content']);
            $this->db->bind(':id', $data['id']);
            
            // Execute the update
            if ($this->db->execute()) {
                return true;
            }
            
            return false;
        } catch (Exception $e) {
            error_log('Error updating reply: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Delete a reply
     * 
     * @param int $id Reply ID
     * @return bool True if successful
     */
    public function deleteReply($id) {
        try {
            // Soft delete the reply
            $this->db->query('UPDATE forum_replies SET status = "deleted", updated_at = NOW() 
                            WHERE id = :id');
            $this->db->bind(':id', $id);
            
            // Execute the update
            if ($this->db->execute()) {
                return true;
            }
            
            return false;
        } catch (Exception $e) {
            error_log('Error deleting reply: ' . $e->getMessage());
            return false;
        }
    }
} 