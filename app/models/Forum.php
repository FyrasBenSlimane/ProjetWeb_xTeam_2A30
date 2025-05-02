<?php

/**
 * Forum Model
 * Handles all database interactions related to community forums
 */
class Forum
{
    private $db;
    private $logger;
    private $isInTransaction = false;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->db = new Database();
        // Create a simple logger to track errors
        $this->logger = function ($message) {
            error_log('[FORUM MODEL] ' . $message);
        };
    }

    /**
     * Get all forum categories with topic and post counts
     * @return array
     */
    public function getCategories()
    {
        try {
            $this->db->query('SELECT c.*, 
                            (SELECT COUNT(*) FROM forum_topics WHERE category_id = c.id) as topic_count,
                            (SELECT COUNT(*) FROM forum_replies p JOIN forum_topics t ON p.topic_id = t.id WHERE t.category_id = c.id) as post_count,
                            (SELECT created_at FROM forum_topics WHERE category_id = c.id ORDER BY created_at DESC LIMIT 1) as last_activity
                            FROM forum_categories c
                            ORDER BY c.display_order ASC');
            return $this->db->resultSet();
        } catch (Exception $e) {
            ($this->logger)("Error getting categories: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get a category by ID with extended info
     * @param int $id Category ID
     * @return object
     */
    public function getCategoryById($id)
    {
        try {
            $this->db->query('SELECT c.*, 
                            (SELECT COUNT(*) FROM forum_topics WHERE category_id = c.id) as topic_count,
                            (SELECT COUNT(*) FROM forum_replies p JOIN forum_topics t ON p.topic_id = t.id WHERE t.category_id = c.id) as post_count
                            FROM forum_categories c 
                            WHERE c.id = :id');
            $this->db->bind(':id', $id);
            return $this->db->single();
        } catch (Exception $e) {
            ($this->logger)("Error getting category by ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get a category by slug with extended info
     * @param string $slug Category slug
     * @return object
     */
    public function getCategoryBySlug($slug)
    {
        try {
            $this->db->query('SELECT c.*, 
                            (SELECT COUNT(*) FROM forum_topics WHERE category_id = c.id) as topic_count,
                            (SELECT COUNT(*) FROM forum_replies p JOIN forum_topics t ON p.topic_id = t.id WHERE t.category_id = c.id) as post_count
                            FROM forum_categories c 
                            WHERE c.slug = :slug');
            $this->db->bind(':slug', $slug);
            return $this->db->single();
        } catch (Exception $e) {
            ($this->logger)("Error getting category by slug: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get count of topics in a category (for pagination)
     * @param int $categoryId Category ID
     * @return int
     */
    public function getTopicCountByCategory($categoryId)
    {
        try {
            $this->db->query('SELECT COUNT(*) as count FROM forum_topics WHERE category_id = :category_id');
            $this->db->bind(':category_id', $categoryId);
            $result = $this->db->single();
            return $result ? $result->count : 0;
        } catch (Exception $e) {
            ($this->logger)("Error counting topics: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get topics in a category with enhanced information and filtering options
     * @param int $categoryId Category ID
     * @param int $limit Number of topics to return
     * @param int $offset Offset for pagination
     * @param string $sort Sort order ('newest', 'popular', 'active')
     * @return array
     */
    public function getTopicsByCategory($categoryId, $limit = 20, $offset = 0, $sort = 'active')
    {
        try {
            $orderBy = 't.is_pinned DESC';

            switch ($sort) {
                case 'newest':
                    $orderBy .= ', t.created_at DESC';
                    break;
                case 'popular':
                    $orderBy .= ', t.views DESC, t.reply_count DESC';
                    break;
                case 'active':
                default:
                    $orderBy .= ', t.last_activity DESC';
                    break;
            }

            $this->db->query('SELECT t.*, c.name as category_name, u.name as author_name, u.profile_image as author_image,
                            (SELECT COUNT(*) FROM forum_replies WHERE topic_id = t.id) as reply_count,
                            (SELECT MAX(created_at) FROM forum_replies WHERE topic_id = t.id) as last_post_date,
                            (SELECT user_id FROM forum_replies WHERE topic_id = t.id ORDER BY created_at DESC LIMIT 1) as last_poster_id,
                            (SELECT name FROM users WHERE id = last_poster_id) as last_poster_name,
                            (SELECT profile_image FROM users WHERE id = last_poster_id) as last_poster_image
                            FROM forum_topics t
                            JOIN forum_categories c ON t.category_id = c.id
                            JOIN users u ON t.user_id = u.id
                            WHERE t.category_id = :category_id
                            ORDER BY ' . $orderBy . '
                            LIMIT :limit OFFSET :offset');
            $this->db->bind(':category_id', $categoryId);
            $this->db->bind(':limit', $limit);
            $this->db->bind(':offset', $offset);
            return $this->db->resultSet();
        } catch (Exception $e) {
            ($this->logger)("Error getting topics by category: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get a topic by ID with additional data
     * @param int $id Topic ID
     * @return object
     */
    public function getTopicById($id)
    {
        try {
            $this->db->query('SELECT t.*, c.name as category_name, c.slug as category_slug, 
                            u.name as author_name, u.profile_image as author_image, u.account_type,
                            (SELECT COUNT(*) FROM forum_topics WHERE user_id = t.user_id) as author_topic_count,
                            (SELECT COUNT(*) FROM forum_replies WHERE user_id = t.user_id) as author_reply_count
                            FROM forum_topics t
                            JOIN forum_categories c ON t.category_id = c.id
                            JOIN users u ON t.user_id = u.id
                            WHERE t.id = :id');
            $this->db->bind(':id', $id);
            return $this->db->single();
        } catch (Exception $e) {
            ($this->logger)("Error getting topic by ID: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get a topic by slug with additional data
     * @param string $slug Topic slug
     * @return object
     */
    public function getTopicBySlug($slug)
    {
        try {
            $this->db->query('SELECT t.*, c.name as category_name, c.slug as category_slug, 
                            u.name as author_name, u.profile_image as author_image, u.account_type,
                            (SELECT COUNT(*) FROM forum_topics WHERE user_id = t.user_id) as author_topic_count,
                            (SELECT COUNT(*) FROM forum_replies WHERE user_id = t.user_id) as author_reply_count
                            FROM forum_topics t
                            JOIN forum_categories c ON t.category_id = c.id
                            JOIN users u ON t.user_id = u.id
                            WHERE t.slug = :slug');
            $this->db->bind(':slug', $slug);
            return $this->db->single();
        } catch (Exception $e) {
            ($this->logger)("Error getting topic by slug: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Create a new topic with improved error handling
     * @param array $data Topic data
     * @return int|bool Topic ID on success, false on failure
     */
    public function createTopic($data)
    {
        try {
            // Generate slug from title
            $slug = $this->createSlug($data['title']);

            // Start transaction
            $this->db->beginTransaction();
            $this->isInTransaction = true;

            // Insert topic
            $this->db->query('INSERT INTO forum_topics (title, slug, content, category_id, user_id, status, last_activity, reply_count) 
                            VALUES (:title, :slug, :content, :category_id, :user_id, :status, NOW(), 0)');

            $this->db->bind(':title', $data['title']);
            $this->db->bind(':slug', $slug);
            $this->db->bind(':content', $data['content']);
            $this->db->bind(':category_id', $data['category_id']);
            $this->db->bind(':user_id', $data['user_id']);
            $this->db->bind(':status', isset($data['status']) ? $data['status'] : 'open');

            // Execute topic insert
            if ($this->db->execute()) {
                $topicId = $this->db->lastInsertId();

                // All operations successful, commit
                $this->db->commit();
                $this->isInTransaction = false;
                return $topicId;
            } else {
                // Topic insertion failed, rollback
                $this->db->rollBack();
                $this->isInTransaction = false;
                return false;
            }
        } catch (Exception $e) {
            ($this->logger)("Error creating topic: " . $e->getMessage());
            if ($this->isInTransaction) {
                $this->db->rollBack();
                $this->isInTransaction = false;
            }
            return false;
        }
    }

    /**
     * Get count of replies for a topic (for pagination)
     * @param int $topicId Topic ID
     * @return int
     */
    public function getReplyCountByTopic($topicId)
    {
        try {
            $this->db->query('SELECT COUNT(*) as count FROM forum_replies WHERE topic_id = :topic_id');
            $this->db->bind(':topic_id', $topicId);
            $result = $this->db->single();
            return $result ? $result->count : 0;
        } catch (Exception $e) {
            ($this->logger)("Error counting replies: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get replies for a topic with pagination and better ordering
     * @param int $topicId Topic ID
     * @param int $limit Number of replies per page
     * @param int $offset Offset for pagination
     * @param bool $includeTopic Whether to include the first post (topic content)
     * @return array
     */
    public function getRepliesByTopicId($topicId, $limit = 20, $offset = 0, $includeTopic = true)
    {
        try {
            // Determine the starting point based on whether we should include the topic post
            $realOffset = $includeTopic ? $offset : ($offset + 1);

            $this->db->query('SELECT r.*, u.name as author_name, u.profile_image as author_image, u.account_type, 
                            (SELECT COUNT(*) FROM forum_topics WHERE user_id = r.user_id) as author_topic_count,
                            (SELECT COUNT(*) FROM forum_replies WHERE user_id = r.user_id) as author_reply_count,
                            (SELECT created_at FROM forum_topics WHERE id = r.topic_id) as topic_created_at
                            FROM forum_replies r
                            JOIN users u ON r.user_id = u.id
                            WHERE r.topic_id = :topic_id
                            ORDER BY r.created_at ASC
                            LIMIT :limit OFFSET :offset');

            $this->db->bind(':topic_id', $topicId);
            $this->db->bind(':limit', $limit);
            $this->db->bind(':offset', $realOffset);

            return $this->db->resultSet();
        } catch (Exception $e) {
            ($this->logger)("Error getting replies: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Create a reply to a topic with improved error handling and notifications
     * @param array $data Post data
     * @return bool|int Reply ID on success, false on failure
     */
    public function createReply($data)
    {
        try {
            $this->db->beginTransaction();
            $this->isInTransaction = true;

            // Insert reply
            $this->db->query('INSERT INTO forum_replies (topic_id, user_id, content, created_at) 
                            VALUES (:topic_id, :user_id, :content, NOW())');

            $this->db->bind(':topic_id', $data['topic_id']);
            $this->db->bind(':user_id', $data['user_id']);
            $this->db->bind(':content', $data['content']);

            // Execute reply insert
            if (!$this->db->execute()) {
                $this->db->rollBack();
                $this->isInTransaction = false;
                return false;
            }

            $replyId = $this->db->lastInsertId();

            // Update topic's last_activity timestamp and reply count
            $this->db->query('UPDATE forum_topics SET last_activity = NOW(), reply_count = reply_count + 1 WHERE id = :topic_id');
            $this->db->bind(':topic_id', $data['topic_id']);

            if (!$this->db->execute()) {
                $this->db->rollBack();
                $this->isInTransaction = false;
                return false;
            }

            $this->db->commit();
            $this->isInTransaction = false;
            return $replyId;
        } catch (Exception $e) {
            ($this->logger)("Error creating reply: " . $e->getMessage());
            if ($this->isInTransaction) {
                $this->db->rollBack();
                $this->isInTransaction = false;
            }
            return false;
        }
    }

    /**
     * Get latest topics across all categories with improved details
     * @param int $limit Number of topics to return
     * @return array
     */
    public function getLatestTopics($limit = 5)
    {
        try {
            $this->db->query('SELECT t.*, c.name as category_name, c.slug as category_slug, u.name as author_name,
                            u.profile_image as author_image,
                            (SELECT COUNT(*) FROM forum_replies WHERE topic_id = t.id) as reply_count,
                            (SELECT MAX(created_at) FROM forum_replies WHERE topic_id = t.id) as last_reply_date
                            FROM forum_topics t
                            JOIN forum_categories c ON t.category_id = c.id
                            JOIN users u ON t.user_id = u.id
                            ORDER BY t.created_at DESC
                            LIMIT :limit');
            $this->db->bind(':limit', $limit);
            return $this->db->resultSet();
        } catch (Exception $e) {
            ($this->logger)("Error getting latest topics: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get popular topics with improved metrics
     * @param int $limit Number of topics to return
     * @param string $period Time period ('day', 'week', 'month', 'all')
     * @return array
     */
    public function getPopularTopics($limit = 5, $period = 'all')
    {
        try {
            $timeConstraint = '';

            switch ($period) {
                case 'day':
                    $timeConstraint = 'AND t.created_at > DATE_SUB(NOW(), INTERVAL 1 DAY)';
                    break;
                case 'week':
                    $timeConstraint = 'AND t.created_at > DATE_SUB(NOW(), INTERVAL 1 WEEK)';
                    break;
                case 'month':
                    $timeConstraint = 'AND t.created_at > DATE_SUB(NOW(), INTERVAL 1 MONTH)';
                    break;
            }

            $this->db->query('SELECT t.*, c.name as category_name, c.slug as category_slug, u.name as author_name,
                            u.profile_image as author_image,
                            (SELECT COUNT(*) FROM forum_replies WHERE topic_id = t.id) as reply_count
                            FROM forum_topics t
                            JOIN forum_categories c ON t.category_id = c.id
                            JOIN users u ON t.user_id = u.id
                            WHERE 1 ' . $timeConstraint . '
                            ORDER BY t.views DESC, t.reply_count DESC
                            LIMIT :limit');

            $this->db->bind(':limit', $limit);
            return $this->db->resultSet();
        } catch (Exception $e) {
            ($this->logger)("Error getting popular topics: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Increment view count for a topic with improved behavior
     * @param int $id Topic ID
     * @param string $viewerIp Visitor IP to prevent duplicate counts
     * @return bool
     */
    public function incrementViewCount($id, $viewerIp = null)
    {
        try {
            // Simple logic to prevent duplicate views from same session
            $sessionKey = 'viewed_topic_' . $id;

            if ($viewerIp && isset($_SESSION[$sessionKey]) && $_SESSION[$sessionKey] === $viewerIp) {
                // Already viewed in this session
                return true;
            }

            $this->db->query('UPDATE forum_topics SET views = views + 1 WHERE id = :id');
            $this->db->bind(':id', $id);
            $result = $this->db->execute();

            // Store in session if successful
            if ($result && $viewerIp) {
                $_SESSION[$sessionKey] = $viewerIp;
            }

            return $result;
        } catch (Exception $e) {
            ($this->logger)("Error incrementing view count: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Enhanced search for topics and posts with filtering options
     * @param string $search Search query
     * @param string $category Category to filter by (optional)
     * @param string $sort Sort order ('relevance', 'newest', 'popular')
     * @param int $limit Limit results
     * @return array
     */
    public function searchForum($search, $category = null, $sort = 'relevance', $limit = 50)
    {
        try {
            $searchTerm = "%{$search}%";
            $categoryFilter = $category ? 'AND c.id = :category_id' : '';

            $orderBy = 'created_at DESC';
            if ($sort === 'popular') {
                $orderBy = 'views DESC, reply_count DESC';
            } else if ($sort === 'relevance') {
                $orderBy = 'CASE WHEN t.title LIKE :exact_match THEN 3
                          WHEN t.title LIKE :start_match THEN 2
                          ELSE 1 END DESC, t.created_at DESC';
            }

            $this->db->query('SELECT t.*, c.name as category_name, c.slug as category_slug, u.name as author_name,
                            u.profile_image as author_image,
                            (SELECT COUNT(*) FROM forum_replies WHERE topic_id = t.id) as reply_count,
                            "topic" as result_type
                            FROM forum_topics t
                            JOIN forum_categories c ON t.category_id = c.id
                            JOIN users u ON t.user_id = u.id
                            WHERE (t.title LIKE :search OR t.content LIKE :search) ' . $categoryFilter . '
                            
                            UNION
                            
                            SELECT t.*, c.name as category_name, c.slug as category_slug, u.name as author_name,
                            u.profile_image as author_image,
                            (SELECT COUNT(*) FROM forum_replies WHERE topic_id = t.id) as reply_count,
                            "post" as result_type
                            FROM forum_replies p
                            JOIN forum_topics t ON p.topic_id = t.id
                            JOIN forum_categories c ON t.category_id = c.id
                            JOIN users u ON t.user_id = u.id
                            WHERE p.content LIKE :search AND p.id > (
                                SELECT MIN(id) FROM forum_replies WHERE topic_id = t.id
                            ) ' . $categoryFilter . '
                            
                            ORDER BY ' . $orderBy . '
                            LIMIT :limit');

            $this->db->bind(':search', $searchTerm);

            if ($sort === 'relevance') {
                $this->db->bind(':exact_match', $search);
                $this->db->bind(':start_match', $search . '%');
            }

            if ($category) {
                $this->db->bind(':category_id', $category);
            }

            $this->db->bind(':limit', $limit);
            return $this->db->resultSet();
        } catch (Exception $e) {
            ($this->logger)("Error searching forum: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get a reply by ID with author info
     * @param int $id Reply ID
     * @return object
     */
    public function getReplyById($id)
    {
        try {
            $this->db->query('SELECT r.*, u.name as author_name, u.profile_image as author_image, u.account_type
                            FROM forum_replies r
                            JOIN users u ON r.user_id = u.id
                            WHERE r.id = :id');
            $this->db->bind(':id', $id);
            return $this->db->single();
        } catch (Exception $e) {
            ($this->logger)("Error getting reply: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update a post with history tracking
     * @param int $id Post ID
     * @param string $content New content
     * @param int $editorId User ID of editor
     * @return bool
     */
    public function updatePost($id, $content, $editorId)
    {
        try {
            $this->db->beginTransaction();
            $this->isInTransaction = true;

            // Get original post content for history
            $original = $this->getReplyById($id);
            if (!$original) {
                return false;
            }

            // Update the post
            $this->db->query('UPDATE forum_replies SET 
                            content = :content, 
                            is_edited = 1, 
                            edited_at = NOW(),
                            editor_id = :editor_id
                            WHERE id = :id');

            $this->db->bind(':content', $content);
            $this->db->bind(':editor_id', $editorId);
            $this->db->bind(':id', $id);

            $result = $this->db->execute();

            if ($result) {
                // We could log edit history in a separate table here
                // if you want to implement an edit history feature
                $this->db->commit();
                $this->isInTransaction = false;
                return true;
            } else {
                $this->db->rollBack();
                $this->isInTransaction = false;
                return false;
            }
        } catch (Exception $e) {
            ($this->logger)("Error updating post: " . $e->getMessage());
            if ($this->isInTransaction) {
                $this->db->rollBack();
                $this->isInTransaction = false;
            }
            return false;
        }
    }

    /**
     * Delete a post with improved transaction safety
     * @param int $id Post ID
     * @return bool
     */
    public function deletePost($id)
    {
        try {
            // Get post info to update reply count
            $post = $this->getReplyById($id);

            if (!$post) {
                return false;
            }

            // Start transaction
            $this->db->beginTransaction();
            $this->isInTransaction = true;

            // Delete the post
            $this->db->query('DELETE FROM forum_replies WHERE id = :id');
            $this->db->bind(':id', $id);
            $postDeleted = $this->db->execute();

            // Update topic reply count
            $this->db->query('UPDATE forum_topics SET reply_count = reply_count - 1 WHERE id = :topic_id');
            $this->db->bind(':topic_id', $post->topic_id);
            $topicUpdated = $this->db->execute();

            // Commit or rollback based on results
            if ($postDeleted && $topicUpdated) {
                $this->db->commit();
                $this->isInTransaction = false;
                return true;
            } else {
                $this->db->rollBack();
                $this->isInTransaction = false;
                return false;
            }
        } catch (Exception $e) {
            ($this->logger)("Error deleting post: " . $e->getMessage());
            if ($this->isInTransaction) {
                $this->db->rollBack();
                $this->isInTransaction = false;
            }
            return false;
        }
    }

    /**
     * Delete a topic and all its posts with improved error handling
     * @param int $id Topic ID
     * @return bool
     */
    public function deleteTopic($id)
    {
        try {
            // Start transaction
            $this->db->beginTransaction();
            $this->isInTransaction = true;

            // Delete all posts in the topic
            $this->db->query('DELETE FROM forum_replies WHERE topic_id = :id');
            $this->db->bind(':id', $id);
            $postsDeleted = $this->db->execute();

            // Delete the topic
            $this->db->query('DELETE FROM forum_topics WHERE id = :id');
            $this->db->bind(':id', $id);
            $topicDeleted = $this->db->execute();

            // Commit or rollback based on results
            if ($postsDeleted && $topicDeleted) {
                $this->db->commit();
                $this->isInTransaction = false;
                return true;
            } else {
                $this->db->rollBack();
                $this->isInTransaction = false;
                return false;
            }
        } catch (Exception $e) {
            ($this->logger)("Error deleting topic: " . $e->getMessage());
            if ($this->isInTransaction) {
                $this->db->rollBack();
                $this->isInTransaction = false;
            }
            return false;
        }
    }

    /**
     * Toggle pinned status for a topic
     * @param int $id Topic ID
     * @param bool $status New pinned status
     * @return bool
     */
    public function togglePinned($id, $status)
    {
        try {
            $this->db->query('UPDATE forum_topics SET is_pinned = :status WHERE id = :id');
            $this->db->bind(':status', $status ? 1 : 0);
            $this->db->bind(':id', $id);
            return $this->db->execute();
        } catch (Exception $e) {
            ($this->logger)("Error toggling pinned status: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Toggle locked status for a topic
     * @param int $id Topic ID
     * @param bool $status New locked status
     * @return bool
     */
    public function toggleLocked($id, $status)
    {
        try {
            $this->db->query('UPDATE forum_topics SET is_locked = :status WHERE id = :id');
            $this->db->bind(':status', $status ? 1 : 0);
            $this->db->bind(':id', $id);
            return $this->db->execute();
        } catch (Exception $e) {
            ($this->logger)("Error toggling locked status: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get user's recent topics with improved info
     * @param int $userId User ID
     * @param int $limit Number of topics to return
     * @param int $offset Offset for pagination
     * @return array
     */
    public function getUserTopics($userId, $limit = 5, $offset = 0)
    {
        try {
            $this->db->query('SELECT t.*, c.name as category_name, c.slug as category_slug,
                            (SELECT COUNT(*) FROM forum_replies WHERE topic_id = t.id) as reply_count,
                            (SELECT MAX(created_at) FROM forum_replies WHERE topic_id = t.id) as last_reply_date
                            FROM forum_topics t
                            JOIN forum_categories c ON t.category_id = c.id
                            WHERE t.user_id = :user_id
                            ORDER BY t.created_at DESC
                            LIMIT :limit OFFSET :offset');

            $this->db->bind(':user_id', $userId);
            $this->db->bind(':limit', $limit);
            $this->db->bind(':offset', $offset);
            return $this->db->resultSet();
        } catch (Exception $e) {
            ($this->logger)("Error getting user topics: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Count total topics by a user
     * @param int $userId User ID
     * @return int
     */
    public function countUserTopics($userId)
    {
        try {
            $this->db->query('SELECT COUNT(*) as count FROM forum_topics WHERE user_id = :user_id');
            $this->db->bind(':user_id', $userId);
            $result = $this->db->single();
            return $result ? $result->count : 0;
        } catch (Exception $e) {
            ($this->logger)("Error counting user topics: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get user's recent posts with improved info
     * @param int $userId User ID
     * @param int $limit Number of posts to return
     * @param int $offset Offset for pagination
     * @return array
     */
    public function getUserPosts($userId, $limit = 5, $offset = 0)
    {
        try {
            $this->db->query('SELECT p.*, t.title as topic_title, t.slug as topic_slug, 
                            c.name as category_name, c.slug as category_slug,
                            t.status as topic_status, t.user_id as topic_author_id
                            FROM forum_replies p
                            JOIN forum_topics t ON p.topic_id = t.id
                            JOIN forum_categories c ON t.category_id = c.id
                            WHERE p.user_id = :user_id
                            ORDER BY p.created_at DESC
                            LIMIT :limit OFFSET :offset');

            $this->db->bind(':user_id', $userId);
            $this->db->bind(':limit', $limit);
            $this->db->bind(':offset', $offset);
            return $this->db->resultSet();
        } catch (Exception $e) {
            ($this->logger)("Error getting user posts: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Count total posts by a user
     * @param int $userId User ID
     * @return int
     */
    public function countUserPosts($userId)
    {
        try {
            $this->db->query('SELECT COUNT(*) as count FROM forum_replies WHERE user_id = :user_id');
            $this->db->bind(':user_id', $userId);
            $result = $this->db->single();
            return $result ? $result->count : 0;
        } catch (Exception $e) {
            ($this->logger)("Error counting user posts: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Create a slug from a string
     * @param string $text Text to convert to slug
     * @return string
     */
    private function createSlug($text)
    {
        // Replace non letter/number characters with hyphen
        $slug = preg_replace('~[^\pL\d]+~u', '-', $text);

        // Transliterate
        if (function_exists('iconv')) {
            $slug = iconv('utf-8', 'us-ascii//TRANSLIT', $slug);
        }

        // Remove unwanted characters
        $slug = preg_replace('~[^-\w]+~', '', $slug);

        // Trim
        $slug = trim($slug, '-');

        // Remove duplicate hyphens
        $slug = preg_replace('~-+~', '-', $slug);

        // Convert to lowercase
        $slug = strtolower($slug);

        // If slug is empty, use a generic identifier with timestamp
        if (empty($slug)) {
            $slug = 'topic-' . time();
        }

        // Check if slug already exists
        $count = 1;
        $originalSlug = $slug;

        while ($this->slugExists($slug)) {
            $slug = $originalSlug . '-' . $count;
            $count++;
        }

        return $slug;
    }

    /**
     * Check if a slug already exists in forum_topics table
     * @param string $slug Slug to check
     * @return bool
     */
    private function slugExists($slug)
    {
        try {
            $this->db->query('SELECT COUNT(*) as count FROM forum_topics WHERE slug = :slug');
            $this->db->bind(':slug', $slug);

            $row = $this->db->single();
            return $row && $row->count > 0;
        } catch (Exception $e) {
            ($this->logger)("Error checking slug existence: " . $e->getMessage());
            return true; // Assume it exists to be safe
        }
    }

    /**
     * Get recent topics from all categories
     * @param int $limit Number of topics to return
     * @return array
     */
    public function getRecentTopics($limit = 8)
    {
        try {
            $this->db->query('SELECT t.*, c.name as category_name, c.slug as category_slug, 
                            u.name as author_name, u.profile_image as author_image,
                            (SELECT COUNT(*) FROM forum_replies WHERE topic_id = t.id) as reply_count,
                            (SELECT MAX(created_at) FROM forum_replies WHERE topic_id = t.id) as last_reply_date
                            FROM forum_topics t
                            JOIN forum_categories c ON t.category_id = c.id
                            JOIN users u ON t.user_id = u.id
                            ORDER BY t.created_at DESC
                            LIMIT :limit');
            $this->db->bind(':limit', $limit);
            return $this->db->resultSet();
        } catch (Exception $e) {
            ($this->logger)("Error getting recent topics: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Mark a reply as solution for a topic
     * @param int $replyId Reply ID
     * @param int $topicId Topic ID
     * @return bool
     */
    public function markAsSolution($replyId, $topicId)
    {
        try {
            // Start transaction
            $this->db->beginTransaction();
            $this->isInTransaction = true;

            // Clear any existing solution for this topic
            $this->db->query('UPDATE forum_replies SET is_solution = 0 WHERE topic_id = :topic_id');
            $this->db->bind(':topic_id', $topicId);
            $clearResult = $this->db->execute();

            // Mark this reply as solution
            $this->db->query('UPDATE forum_replies SET is_solution = 1 WHERE id = :id');
            $this->db->bind(':id', $replyId);
            $markResult = $this->db->execute();

            // Update topic status to indicate it has a solution
            $this->db->query('UPDATE forum_topics SET has_solution = 1 WHERE id = :id');
            $this->db->bind(':id', $topicId);
            $topicResult = $this->db->execute();

            // Commit or rollback based on results
            if ($clearResult && $markResult && $topicResult) {
                $this->db->commit();
                $this->isInTransaction = false;
                return true;
            } else {
                $this->db->rollBack();
                $this->isInTransaction = false;
                return false;
            }
        } catch (Exception $e) {
            ($this->logger)("Error marking solution: " . $e->getMessage());
            if ($this->isInTransaction) {
                $this->db->rollBack();
                $this->isInTransaction = false;
            }
            return false;
        }
    }

    /**
     * Update topic status with improved error handling
     * @param int $id Topic ID
     * @param string $status New status (open, closed, pinned, archived)
     * @return bool
     */
    public function updateTopicStatus($id, $status)
    {
        try {
            $validStatuses = ['open', 'closed', 'pinned', 'archived'];

            if (!in_array($status, $validStatuses)) {
                return false;
            }

            $this->db->query('UPDATE forum_topics SET status = :status WHERE id = :id');
            $this->db->bind(':status', $status);
            $this->db->bind(':id', $id);
            return $this->db->execute();
        } catch (Exception $e) {
            ($this->logger)("Error updating topic status: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get forum statistics for dashboard
     * @return array
     */
    public function getForumStats()
    {
        try {
            $stats = [];

            // Total topics
            $this->db->query('SELECT COUNT(*) as count FROM forum_topics');
            $result = $this->db->single();
            $stats['total_topics'] = $result ? $result->count : 0;

            // Total replies
            $this->db->query('SELECT COUNT(*) as count FROM forum_replies');
            $result = $this->db->single();
            $stats['total_replies'] = $result ? $result->count : 0;

            // Topics today
            $this->db->query('SELECT COUNT(*) as count FROM forum_topics WHERE created_at > CURDATE()');
            $result = $this->db->single();
            $stats['topics_today'] = $result ? $result->count : 0;

            // Most active category
            $this->db->query('SELECT c.name, c.id, COUNT(t.id) as topic_count 
                            FROM forum_categories c
                            JOIN forum_topics t ON c.id = t.category_id
                            GROUP BY c.id
                            ORDER BY topic_count DESC
                            LIMIT 1');
            $stats['most_active_category'] = $this->db->single();

            // Most viewed topic
            $this->db->query('SELECT id, title, slug, views FROM forum_topics ORDER BY views DESC LIMIT 1');
            $stats['most_viewed_topic'] = $this->db->single();

            // Most replied topic
            $this->db->query('SELECT id, title, slug, reply_count FROM forum_topics ORDER BY reply_count DESC LIMIT 1');
            $stats['most_replied_topic'] = $this->db->single();

            return $stats;
        } catch (Exception $e) {
            ($this->logger)("Error getting forum stats: " . $e->getMessage());
            return [
                'total_topics' => 0,
                'total_replies' => 0,
                'topics_today' => 0
            ];
        }
    }

    /**
     * Get topics with no replies (for admin attention)
     * @param int $limit Limit
     * @return array
     */
    public function getUnansweredTopics($limit = 10)
    {
        try {
            $this->db->query('SELECT t.*, c.name as category_name, c.slug as category_slug, 
                            u.name as author_name, u.profile_image as author_image
                            FROM forum_topics t
                            JOIN forum_categories c ON t.category_id = c.id
                            JOIN users u ON t.user_id = u.id
                            WHERE t.reply_count <= 1 AND t.status = "open"
                            ORDER BY t.created_at ASC
                            LIMIT :limit');
            $this->db->bind(':limit', $limit);
            return $this->db->resultSet();
        } catch (Exception $e) {
            ($this->logger)("Error getting unanswered topics: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Create a new forum category
     * @param array $data Category data
     * @return int|bool Category ID on success, false on failure
     */
    public function createCategory($data)
    {
        try {
            // Generate slug from name
            $slug = $this->createSlug($data['name']);

            $this->db->query('INSERT INTO forum_categories (name, description, slug, icon, display_order) 
                            VALUES (:name, :description, :slug, :icon, :display_order)');

            $this->db->bind(':name', $data['name']);
            $this->db->bind(':description', $data['description'] ?? '');
            $this->db->bind(':slug', $slug);
            $this->db->bind(':icon', $data['icon'] ?? 'chat');
            $this->db->bind(':display_order', $data['display_order'] ?? 0);

            if ($this->db->execute()) {
                return $this->db->lastInsertId();
            }

            return false;
        } catch (Exception $e) {
            ($this->logger)("Error creating category: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update a forum category
     * @param array $data Category data with id
     * @return bool
     */
    public function updateCategory($data)
    {
        try {
            $query = 'UPDATE forum_categories SET 
                      name = :name,
                      description = :description';

            // Add optional parameters if they exist
            if (isset($data['icon']) && !is_null($data['icon'])) {
                $query .= ', icon = :icon';
            }
            if (isset($data['color']) && !is_null($data['color'])) {
                $query .= ', color = :color';
            }
            if (isset($data['display_order'])) {
                $query .= ', display_order = :display_order';
            }

            // Add permission fields if they exist
            if (isset($data['perm_all_view'])) {
                $query .= ', perm_all_view = :perm_all_view';
            }
            if (isset($data['perm_all_create'])) {
                $query .= ', perm_all_create = :perm_all_create';
            }
            if (isset($data['perm_all_reply'])) {
                $query .= ', perm_all_reply = :perm_all_reply';
            }

            $query .= ' WHERE id = :id';

            $this->db->query($query);

            $this->db->bind(':name', $data['name']);
            $this->db->bind(':description', $data['description'] ?? '');
            $this->db->bind(':id', $data['id']);

            // Bind optional parameters if they exist
            if (isset($data['icon']) && !is_null($data['icon'])) {
                $this->db->bind(':icon', $data['icon']);
            }
            if (isset($data['color']) && !is_null($data['color'])) {
                $this->db->bind(':color', $data['color']);
            }
            if (isset($data['display_order'])) {
                $this->db->bind(':display_order', $data['display_order']);
            }

            // Bind permission fields if they exist
            if (isset($data['perm_all_view'])) {
                $this->db->bind(':perm_all_view', $data['perm_all_view']);
            }
            if (isset($data['perm_all_create'])) {
                $this->db->bind(':perm_all_create', $data['perm_all_create']);
            }
            if (isset($data['perm_all_reply'])) {
                $this->db->bind(':perm_all_reply', $data['perm_all_reply']);
            }

            return $this->db->execute();
        } catch (Exception $e) {
            ($this->logger)("Error updating category: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete a forum category and all associated topics/replies
     * @param int $id Category ID
     * @return bool
     */
    public function deleteCategory($id)
    {
        try {
            $this->db->beginTransaction();

            // Get all topics in this category
            $this->db->query('SELECT id FROM forum_topics WHERE category_id = :category_id');
            $this->db->bind(':category_id', $id);
            $topics = $this->db->resultSet();

            // Delete all replies for these topics
            foreach ($topics as $topic) {
                $this->db->query('DELETE FROM forum_replies WHERE topic_id = :topic_id');
                $this->db->bind(':topic_id', $topic->id);
                $this->db->execute();
            }

            // Delete all topics in this category
            $this->db->query('DELETE FROM forum_topics WHERE category_id = :category_id');
            $this->db->bind(':category_id', $id);
            $this->db->execute();

            // Delete the category
            $this->db->query('DELETE FROM forum_categories WHERE id = :id');
            $this->db->bind(':id', $id);
            $categoryDeleted = $this->db->execute();

            if ($categoryDeleted) {
                $this->db->commit();
                return true;
            } else {
                $this->db->rollBack();
                return false;
            }
        } catch (Exception $e) {
            ($this->logger)("Error deleting category: " . $e->getMessage());
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            return false;
        }
    }

    /**
     * Get topics for moderation
     * @param int $limit Limit
     * @param int $offset Offset
     * @return array
     */
    public function getReportedTopics($limit = 10, $offset = 0)
    {
        try {
            // This method would require a reports table or flagging system
            // For now, returning an empty array
            return [];
        } catch (Exception $e) {
            ($this->logger)("Error getting reported topics: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Delete a reply with proper error handling and topic update
     * @param int $id Reply ID
     * @return bool
     */
    public function deleteReply($id)
    {
        try {
            // Get the reply to find its topic ID
            $reply = $this->getReplyById($id);

            if (!$reply) {
                return false;
            }

            $topicId = $reply->topic_id;

            // Start transaction
            $this->db->beginTransaction();
            $this->isInTransaction = true;

            // Delete the reply
            $this->db->query('DELETE FROM forum_replies WHERE id = :id');
            $this->db->bind(':id', $id);

            if (!$this->db->execute()) {
                $this->db->rollBack();
                $this->isInTransaction = false;
                return false;
            }

            // Update the topic's reply count
            $this->db->query('UPDATE forum_topics SET reply_count = reply_count - 1 WHERE id = :topic_id');
            $this->db->bind(':topic_id', $topicId);

            if (!$this->db->execute()) {
                $this->db->rollBack();
                $this->isInTransaction = false;
                return false;
            }

            // Commit the transaction
            $this->db->commit();
            $this->isInTransaction = false;
            return true;
        } catch (Exception $e) {
            ($this->logger)("Error deleting reply: " . $e->getMessage());
            if ($this->isInTransaction) {
                $this->db->rollBack();
                $this->isInTransaction = false;
            }
            return false;
        }
    }

    /**
     * Search topics with improved filtering options
     * @param string $search Search query
     * @param string $sort Sort order ('recent', 'popular', 'relevance')
     * @param string $filter Filter option ('all', 'solved', 'open', 'closed')
     * @param int $limit Number of results to return
     * @return array
     */
    public function searchTopics($search, $sort = 'recent', $filter = 'all', $limit = 50)
    {
        try {
            $searchTerm = "%{$search}%";

            // Base query
            $sql = 'SELECT t.*, c.name as category_name, c.slug as category_slug, 
                    u.name as author_name, u.profile_image,
                    (SELECT COUNT(*) FROM forum_replies WHERE topic_id = t.id) as reply_count
                    FROM forum_topics t
                    JOIN forum_categories c ON t.category_id = c.id
                    JOIN users u ON t.user_id = u.id
                    WHERE (t.title LIKE :search OR t.content LIKE :search) ';

            // Add filter constraints
            if ($filter == 'solved') {
                $sql .= 'AND t.has_solution = 1 ';
            } elseif ($filter == 'open') {
                $sql .= 'AND t.status = "open" ';
            } elseif ($filter == 'closed') {
                $sql .= 'AND t.status = "closed" ';
            }

            // Add sort order
            if ($sort == 'recent') {
                $sql .= 'ORDER BY t.created_at DESC ';
            } elseif ($sort == 'popular') {
                $sql .= 'ORDER BY t.views DESC, t.reply_count DESC ';
            } elseif ($sort == 'relevance') {
                $sql .= 'ORDER BY 
                        CASE 
                            WHEN t.title LIKE :exact_match THEN 3
                            WHEN t.title LIKE :start_match THEN 2
                            ELSE 1 
                        END DESC, 
                        t.created_at DESC ';
            }

            // Add limit
            $sql .= 'LIMIT :limit';

            $this->db->query($sql);
            $this->db->bind(':search', $searchTerm);

            if ($sort == 'relevance') {
                $this->db->bind(':exact_match', $search);
                $this->db->bind(':start_match', $search . '%');
            }

            $this->db->bind(':limit', $limit);
            return $this->db->resultSet();
        } catch (Exception $e) {
            ($this->logger)("Error searching topics: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get all topics with pagination and sorting options
     * @param int $limit Number of topics per page
     * @param int $offset Pagination offset
     * @param string $sort Sort order ('recent', 'popular', 'views', 'replies')
     * @return array
     */
    public function getAllTopics($limit = 20, $offset = 0, $sort = 'recent')
    {
        try {
            // Determine sort order
            $orderBy = '';

            if ($sort == 'recent') {
                $orderBy = 't.created_at DESC';
            } elseif ($sort == 'popular') {
                $orderBy = 't.views DESC, t.reply_count DESC';
            } elseif ($sort == 'views') {
                $orderBy = 't.views DESC';
            } elseif ($sort == 'replies') {
                $orderBy = 't.reply_count DESC';
            } else {
                $orderBy = 't.created_at DESC'; // Default
            }

            $this->db->query('SELECT t.*, c.name as category_name, c.slug as category_slug, 
                            u.name as author_name, u.profile_image,
                            (SELECT COUNT(*) FROM forum_replies WHERE topic_id = t.id) as reply_count,
                            (SELECT MAX(created_at) FROM forum_replies WHERE topic_id = t.id) as last_reply_at
                            FROM forum_topics t
                            JOIN forum_categories c ON t.category_id = c.id
                            JOIN users u ON t.user_id = u.id
                            ORDER BY t.status = "pinned" DESC, ' . $orderBy . '
                            LIMIT :limit OFFSET :offset');

            $this->db->bind(':limit', $limit);
            $this->db->bind(':offset', $offset);
            return $this->db->resultSet();
        } catch (Exception $e) {
            ($this->logger)("Error getting all topics: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Count all topics for pagination
     * @return int
     */
    public function countAllTopics()
    {
        try {
            $this->db->query('SELECT COUNT(*) as count FROM forum_topics');
            $result = $this->db->single();
            return $result ? $result->count : 0;
        } catch (Exception $e) {
            ($this->logger)("Error counting all topics: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Count all replies for dashboard stats
     * @return int
     */
    public function countAllReplies()
    {
        try {
            $this->db->query('SELECT COUNT(*) as count FROM forum_replies');
            $result = $this->db->single();
            return $result ? $result->count : 0;
        } catch (Exception $e) {
            ($this->logger)("Error counting all replies: " . $e->getMessage());
            return 0;
        }
    }

    /**
     * Get recent replies across all topics with author info
     * @param int $limit Number of replies to return
     * @return array
     */
    public function getRecentReplies($limit = 10)
    {
        try {
            $this->db->query('SELECT r.*, t.title as topic_title, t.slug as topic_slug, 
                            t.status as topic_status, u.name as author_name, 
                            u.profile_image as author_image, u.account_type
                            FROM forum_replies r
                            JOIN forum_topics t ON r.topic_id = t.id
                            JOIN users u ON r.user_id = u.id
                            ORDER BY r.created_at DESC
                            LIMIT :limit');
            $this->db->bind(':limit', $limit);
            return $this->db->resultSet();
        } catch (Exception $e) {
            ($this->logger)("Error getting recent replies: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get reported forum content for moderation
     * @param int $limit Number of reports to return
     * @return array
     */
    public function getReportedContent($limit = 10)
    {
        try {
            // In a real implementation, this would query a forum_reports table
            // For now, simulate with a placeholder implementation
            return [];

            /*
            $this->db->query('SELECT r.*, 
                            CASE WHEN r.content_type = "topic" 
                                THEN t.title 
                                ELSE CONCAT("Reply in: ", t2.title) 
                            END as content_title,
                            u.name as reporter_name,
                            u2.name as content_author_name
                            FROM forum_reports r
                            LEFT JOIN forum_topics t ON r.content_type = "topic" AND r.content_id = t.id
                            LEFT JOIN forum_replies p ON r.content_type = "reply" AND r.content_id = p.id
                            LEFT JOIN forum_topics t2 ON r.content_type = "reply" AND p.topic_id = t2.id
                            LEFT JOIN users u ON r.reported_by = u.id
                            LEFT JOIN users u2 ON 
                                (r.content_type = "topic" AND t.user_id = u2.id) OR
                                (r.content_type = "reply" AND p.user_id = u2.id)
                            WHERE r.status = "pending"
                            ORDER BY r.created_at DESC
                            LIMIT :limit');
            $this->db->bind(':limit', $limit);
            return $this->db->resultSet();
            */
        } catch (Exception $e) {
            ($this->logger)("Error getting reported content: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get a report by ID
     * @param int $id Report ID
     * @return object|bool
     */
    public function getReportById($id)
    {
        // This is a placeholder - in a real implementation, this would query a forum_reports table
        return false;
    }

    /**
     * Dismiss a report
     * @param int $id Report ID
     * @return bool
     */
    public function dismissReport($id)
    {
        // This is a placeholder - in a real implementation, this would update a forum_reports table
        return true;
    }

    /**
     * Update a reply with new content (admin function)
     * @param int $id Reply ID
     * @param string $content New content
     * @return bool
     */
    public function updateReply($id, $content)
    {
        try {
            $this->db->query('UPDATE forum_replies SET 
                            content = :content, 
                            is_edited = 1, 
                            edited_at = NOW(),
                            editor_id = :editor_id
                            WHERE id = :id');

            $this->db->bind(':content', $content);
            $this->db->bind(':editor_id', $_SESSION['user_id']);
            $this->db->bind(':id', $id);

            return $this->db->execute();
        } catch (Exception $e) {
            ($this->logger)("Error updating reply: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update reply status (e.g., approve, flag)
     * @param int $id Reply ID
     * @param string $status New status
     * @return bool
     */
    public function updateReplyStatus($id, $status)
    {
        try {
            $validStatuses = ['pending', 'approved', 'flagged'];

            if (!in_array($status, $validStatuses)) {
                return false;
            }

            $this->db->query('UPDATE forum_replies SET status = :status WHERE id = :id');
            $this->db->bind(':status', $status);
            $this->db->bind(':id', $id);
            return $this->db->execute();
        } catch (Exception $e) {
            ($this->logger)("Error updating reply status: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Update a topic with improved error handling
     * @param array $data Topic data
     * @return bool
     */
    public function updateTopic($data)
    {
        try {
            // Start transaction
            $this->db->beginTransaction();
            $this->isInTransaction = true;

            // Update topic
            $this->db->query('UPDATE forum_topics SET 
                            title = :title, 
                            content = :content,
                            category_id = :category_id, 
                            is_edited = 1,
                            edited_at = NOW(),
                            last_activity = NOW() 
                            WHERE id = :id');

            $this->db->bind(':title', $data['title']);
            $this->db->bind(':content', $data['content']);
            $this->db->bind(':category_id', $data['category_id']);
            $this->db->bind(':id', $data['id']);

            // Execute update
            if (!$this->db->execute()) {
                $this->db->rollBack();
                $this->isInTransaction = false;
                return false;
            }

            // Check if we need to update the slug
            $topic = $this->getTopicById($data['id']);
            $newSlug = $this->createSlug($data['title']);

            if ($topic->slug !== $newSlug) {
                $this->db->query('UPDATE forum_topics SET slug = :slug WHERE id = :id');
                $this->db->bind(':slug', $newSlug);
                $this->db->bind(':id', $data['id']);

                if (!$this->db->execute()) {
                    $this->db->rollBack();
                    $this->isInTransaction = false;
                    return false;
                }
            }

            // Commit transaction
            $this->db->commit();
            $this->isInTransaction = false;
            return true;
        } catch (Exception $e) {
            ($this->logger)("Error updating topic: " . $e->getMessage());
            if ($this->isInTransaction) {
                $this->db->rollBack();
                $this->isInTransaction = false;
            }
            return false;
        }
    }
}
