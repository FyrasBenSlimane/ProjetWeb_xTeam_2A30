<?php

/**
 * Group Model
 * Handles all database interactions related to community groups
 */
class Group
{
    private $db;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->db = new Database();
    }

    /**
     * Get featured groups
     * @param int $limit Number of groups to return
     * @return array
     */
    public function getFeaturedGroups($limit = 3)
    {
        $this->db->query('SELECT g.*, u.name as creator_name, 
                        (SELECT COUNT(*) FROM community_group_members WHERE group_id = g.id) as member_count
                        FROM community_groups g 
                        JOIN users u ON g.creator_id = u.id
                        WHERE g.is_featured = 1
                        ORDER BY g.created_at DESC
                        LIMIT :limit');
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    /**
     * Get recent groups
     * @param int $limit Number of groups to return
     * @return array
     */
    public function getRecentGroups($limit = 8)
    {
        $this->db->query('SELECT g.*, u.name as creator_name, 
                        (SELECT COUNT(*) FROM community_group_members WHERE group_id = g.id) as member_count
                        FROM community_groups g 
                        JOIN users u ON g.creator_id = u.id
                        WHERE g.is_private = 0
                        ORDER BY g.created_at DESC
                        LIMIT :limit');
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    /**
     * Get a group by ID
     * @param int $id Group ID
     * @return object
     */
    public function getGroupById($id)
    {
        $this->db->query('SELECT g.*, u.name as creator_name, u.profile_image as creator_image 
                        FROM community_groups g
                        JOIN users u ON g.creator_id = u.id
                        WHERE g.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Get a group by slug
     * @param string $slug Group slug
     * @return object
     */
    public function getGroupBySlug($slug)
    {
        $this->db->query('SELECT g.*, u.name as creator_name, u.profile_image as creator_image 
                        FROM community_groups g
                        JOIN users u ON g.creator_id = u.id
                        WHERE g.slug = :slug');
        $this->db->bind(':slug', $slug);
        return $this->db->single();
    }

    /**
     * Create a new group
     * @param array $data Group data
     * @return bool
     */
    public function createGroup($data)
    {
        // Generate slug from name
        $slug = $this->createSlug($data['name']);

        // Insert group
        $this->db->query('INSERT INTO community_groups (name, slug, description, is_private, creator_id, cover_image) 
                        VALUES (:name, :slug, :description, :is_private, :creator_id, :cover_image)');

        $this->db->bind(':name', $data['name']);
        $this->db->bind(':slug', $slug);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':is_private', $data['is_private']);
        $this->db->bind(':creator_id', $data['user_id']);
        $this->db->bind(':cover_image', $data['cover_image'] ?? null);

        // If group is created successfully, add creator as member
        if ($this->db->execute()) {
            $groupId = $this->db->lastInsertId();
            return $this->addMember($data['user_id'], $groupId, 'admin');
        }

        return false;
    }

    /**
     * Get group members
     * @param int $groupId Group ID
     * @return array
     */
    public function getGroupMembers($groupId)
    {
        $this->db->query('SELECT m.*, u.name, u.profile_image, u.account_type 
                        FROM community_group_members m
                        JOIN users u ON m.user_id = u.id
                        WHERE m.group_id = :group_id
                        ORDER BY m.role = "admin" DESC, m.joined_at ASC');
        $this->db->bind(':group_id', $groupId);
        return $this->db->resultSet();
    }

    /**
     * Get group posts
     * @param int $groupId Group ID
     * @param int $limit Number of posts to return
     * @param int $offset Offset for pagination
     * @return array
     */
    public function getGroupPosts($groupId, $limit = 20, $offset = 0)
    {
        $this->db->query('SELECT p.*, u.name as author_name, u.profile_image as author_image 
                        FROM community_group_posts p
                        JOIN users u ON p.user_id = u.id
                        WHERE p.group_id = :group_id
                        ORDER BY p.created_at DESC
                        LIMIT :limit OFFSET :offset');
        $this->db->bind(':group_id', $groupId);
        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);
        return $this->db->resultSet();
    }

    /**
     * Check if user is a member of a group
     * @param int $userId User ID
     * @param int $groupId Group ID
     * @return bool
     */
    public function isUserMember($userId, $groupId)
    {
        $this->db->query('SELECT * FROM community_group_members 
                        WHERE user_id = :user_id AND group_id = :group_id');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':group_id', $groupId);

        $result = $this->db->single();
        return !empty($result);
    }

    /**
     * Add a member to a group
     * @param int $userId User ID
     * @param int $groupId Group ID
     * @param string $role Role (default: member)
     * @return bool
     */
    public function addMember($userId, $groupId, $role = 'member')
    {
        // Check if user is already member
        if ($this->isUserMember($userId, $groupId)) {
            return true;
        }

        $this->db->query('INSERT INTO community_group_members (user_id, group_id, role) 
                        VALUES (:user_id, :group_id, :role)');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':group_id', $groupId);
        $this->db->bind(':role', $role);
        return $this->db->execute();
    }

    /**
     * Remove a member from a group
     * @param int $userId User ID
     * @param int $groupId Group ID
     * @return bool
     */
    public function removeMember($userId, $groupId)
    {
        $this->db->query('DELETE FROM community_group_members 
                        WHERE user_id = :user_id AND group_id = :group_id');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':group_id', $groupId);
        return $this->db->execute();
    }

    /**
     * Create a join request for private groups
     * @param int $userId User ID
     * @param int $groupId Group ID
     * @return bool
     */
    public function createJoinRequest($userId, $groupId)
    {
        // Check if request already exists
        $this->db->query('SELECT * FROM community_group_requests 
                        WHERE user_id = :user_id AND group_id = :group_id');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':group_id', $groupId);

        if ($this->db->single()) {
            return true; // Request already exists
        }

        // Create new request
        $this->db->query('INSERT INTO community_group_requests (user_id, group_id) 
                        VALUES (:user_id, :group_id)');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':group_id', $groupId);
        return $this->db->execute();
    }

    /**
     * Get pending join requests for a group
     * @param int $groupId Group ID
     * @return array
     */
    public function getJoinRequests($groupId)
    {
        $this->db->query('SELECT r.*, u.name, u.profile_image, u.account_type 
                        FROM community_group_requests r
                        JOIN users u ON r.user_id = u.id
                        WHERE r.group_id = :group_id AND r.status = "pending"
                        ORDER BY r.created_at ASC');
        $this->db->bind(':group_id', $groupId);
        return $this->db->resultSet();
    }

    /**
     * Approve a join request
     * @param int $requestId Request ID
     * @return bool
     */
    public function approveJoinRequest($requestId)
    {
        // Get request details
        $this->db->query('SELECT * FROM community_group_requests WHERE id = :id');
        $this->db->bind(':id', $requestId);
        $request = $this->db->single();

        if (!$request) {
            return false;
        }

        // Start transaction
        $this->db->beginTransaction();

        // Update request status
        $this->db->query('UPDATE community_group_requests SET status = "approved" WHERE id = :id');
        $this->db->bind(':id', $requestId);
        $statusUpdated = $this->db->execute();

        // Add member
        $memberAdded = $this->addMember($request->user_id, $request->group_id);

        // Commit or rollback based on results
        if ($statusUpdated && $memberAdded) {
            $this->db->commit();
            return true;
        } else {
            $this->db->rollBack();
            return false;
        }
    }

    /**
     * Reject a join request
     * @param int $requestId Request ID
     * @return bool
     */
    public function rejectJoinRequest($requestId)
    {
        $this->db->query('UPDATE community_group_requests SET status = "rejected" WHERE id = :id');
        $this->db->bind(':id', $requestId);
        return $this->db->execute();
    }

    /**
     * Create a post in a group
     * @param array $data Post data
     * @return bool
     */
    public function createPost($data)
    {
        $this->db->query('INSERT INTO community_group_posts (group_id, user_id, content, attachment) 
                        VALUES (:group_id, :user_id, :content, :attachment)');
        $this->db->bind(':group_id', $data['group_id']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':content', $data['content']);
        $this->db->bind(':attachment', $data['attachment'] ?? null);

        // Update group's last_activity timestamp if post is added
        if ($this->db->execute()) {
            $this->db->query('UPDATE community_groups SET last_activity = NOW() WHERE id = :group_id');
            $this->db->bind(':group_id', $data['group_id']);
            return $this->db->execute();
        }

        return false;
    }

    /**
     * Get a post by ID
     * @param int $id Post ID
     * @return object
     */
    public function getPostById($id)
    {
        $this->db->query('SELECT * FROM community_group_posts WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Delete a post
     * @param int $postId Post ID
     * @return bool
     */
    public function deletePost($postId)
    {
        // Get post details to check for attachments
        $post = $this->getPostById($postId);

        if ($post && !empty($post->attachment)) {
            // Remove attachment file if exists
            $attachmentPath = APPROOT . '/../public/uploads/group_attachments/' . $post->attachment;
            if (file_exists($attachmentPath)) {
                unlink($attachmentPath);
            }
        }

        // Delete the post
        $this->db->query('DELETE FROM community_group_posts WHERE id = :id');
        $this->db->bind(':id', $postId);
        return $this->db->execute();
    }

    /**
     * Get groups that a user is a member of
     * @param int $userId User ID
     * @return array
     */
    public function getUserGroups($userId)
    {
        $this->db->query('SELECT g.*, 
                        (SELECT COUNT(*) FROM community_group_members WHERE group_id = g.id) as member_count
                        FROM community_groups g
                        JOIN community_group_members m ON g.id = m.group_id
                        WHERE m.user_id = :user_id
                        ORDER BY g.last_activity DESC');
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    /**
     * Search for groups
     * @param string $search Search query
     * @param string $sort Sort order (newest, popular, etc.)
     * @return array
     */
    public function searchGroups($search, $sort = 'newest')
    {
        $searchTerm = "%{$search}%";

        $sortQuery = 'g.created_at DESC'; // Default sort by newest

        switch ($sort) {
            case 'popular':
                $sortQuery = 'member_count DESC';
                break;
            case 'active':
                $sortQuery = 'g.last_activity DESC';
                break;
            case 'alphabetical':
                $sortQuery = 'g.name ASC';
                break;
        }

        $this->db->query('SELECT g.*, u.name as creator_name, 
                        (SELECT COUNT(*) FROM community_group_members WHERE group_id = g.id) as member_count
                        FROM community_groups g
                        JOIN users u ON g.creator_id = u.id
                        WHERE (g.name LIKE :search OR g.description LIKE :search)
                        AND g.is_private = 0
                        ORDER BY ' . $sortQuery);

        $this->db->bind(':search', $searchTerm);
        return $this->db->resultSet();
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
        $slug = iconv('utf-8', 'us-ascii//TRANSLIT', $slug);

        // Remove unwanted characters
        $slug = preg_replace('~[^-\w]+~', '', $slug);

        // Trim
        $slug = trim($slug, '-');

        // Remove duplicate hyphens
        $slug = preg_replace('~-+~', '-', $slug);

        // Convert to lowercase
        $slug = strtolower($slug);

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
     * Check if a slug already exists in community_groups table
     * @param string $slug Slug to check
     * @return bool
     */
    private function slugExists($slug)
    {
        $this->db->query('SELECT COUNT(*) as count FROM community_groups WHERE slug = :slug');
        $this->db->bind(':slug', $slug);

        $row = $this->db->single();
        return $row->count > 0;
    }
}
