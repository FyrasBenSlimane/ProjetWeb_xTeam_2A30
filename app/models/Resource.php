<?php

/**
 * Resource Model
 * Handles all database interactions related to community resources
 */
class Resource
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
     * Get all resource categories
     * @return array
     */
    public function getCategories()
    {
        $this->db->query('SELECT * FROM resource_categories ORDER BY name ASC');
        return $this->db->resultSet();
    }

    /**
     * Get a category by ID
     * @param int $id Category ID
     * @return object
     */
    public function getCategoryById($id)
    {
        $this->db->query('SELECT * FROM resource_categories WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Get featured resources
     * @param int $limit Number of resources to return
     * @return array
     */
    public function getFeaturedResources($limit = 3)
    {
        $this->db->query('SELECT r.*, c.name as category_name, u.name as author_name 
                         FROM resources r
                         JOIN resource_categories c ON r.category_id = c.id
                         JOIN users u ON r.user_id = u.id
                         WHERE r.is_featured = 1
                         ORDER BY r.created_at DESC
                         LIMIT :limit');
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    /**
     * Get recent resources
     * @param int $limit Number of resources to return
     * @return array
     */
    public function getRecentResources($limit = 8)
    {
        $this->db->query('SELECT r.*, c.name as category_name, u.name as author_name 
                         FROM resources r
                         JOIN resource_categories c ON r.category_id = c.id
                         JOIN users u ON r.user_id = u.id
                         ORDER BY r.created_at DESC
                         LIMIT :limit');
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    /**
     * Get resources by category
     * @param int $categoryId Category ID
     * @param int $limit Number of resources to return
     * @return array
     */
    public function getResourcesByCategory($categoryId, $limit = 20)
    {
        $this->db->query('SELECT r.*, u.name as author_name 
                         FROM resources r
                         JOIN users u ON r.user_id = u.id
                         WHERE r.category_id = :category_id
                         ORDER BY r.created_at DESC
                         LIMIT :limit');
        $this->db->bind(':category_id', $categoryId);
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    /**
     * Get a resource by ID
     * @param int $id Resource ID
     * @return object
     */
    public function getResourceById($id)
    {
        $this->db->query('SELECT r.*, c.name as category_name 
                         FROM resources r
                         JOIN resource_categories c ON r.category_id = c.id
                         WHERE r.id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    /**
     * Create a new resource
     * @param array $data Resource data
     * @return int|bool Resource ID on success, false on failure
     */
    public function createResource($data)
    {
        // Start transaction
        $this->db->beginTransaction();

        // Insert resource
        $this->db->query('INSERT INTO resources (title, category_id, resource_type, description, content, external_link, file_path, thumbnail, user_id)
                         VALUES (:title, :category_id, :resource_type, :description, :content, :external_link, :file_path, :thumbnail, :user_id)');

        $this->db->bind(':title', $data['title']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':resource_type', $data['resource_type']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':content', $data['content'] ?? null);
        $this->db->bind(':external_link', $data['external_link'] ?? null);
        $this->db->bind(':file_path', $data['file_path'] ?? null);
        $this->db->bind(':thumbnail', $data['thumbnail'] ?? null);
        $this->db->bind(':user_id', $data['user_id']);

        // Execute resource insert
        if ($this->db->execute()) {
            $resourceId = $this->db->lastInsertId();

            // Add tags if present
            if (!empty($data['tags'])) {
                $tagInsertSuccess = true;

                foreach ($data['tags'] as $tagId) {
                    $this->db->query('INSERT INTO resource_tag_relations (resource_id, tag_id) 
                                     VALUES (:resource_id, :tag_id)');
                    $this->db->bind(':resource_id', $resourceId);
                    $this->db->bind(':tag_id', $tagId);

                    if (!$this->db->execute()) {
                        $tagInsertSuccess = false;
                    }
                }

                // If tag insertion fails, rollback
                if (!$tagInsertSuccess) {
                    $this->db->rollBack();
                    return false;
                }
            }

            // All operations successful, commit
            $this->db->commit();
            return $resourceId;
        } else {
            // Resource insertion failed, rollback
            $this->db->rollBack();
            return false;
        }
    }

    /**
     * Increment view count for a resource
     * @param int $id Resource ID
     * @return bool
     */
    public function incrementViewCount($id)
    {
        $this->db->query('UPDATE resources SET view_count = view_count + 1 WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    /**
     * Increment download count for a resource
     * @param int $id Resource ID
     * @return bool
     */
    public function incrementDownloadCount($id)
    {
        $this->db->query('UPDATE resources SET download_count = download_count + 1 WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    /**
     * Get all tags
     * @return array
     */
    public function getTags()
    {
        $this->db->query('SELECT * FROM resource_tags ORDER BY name ASC');
        return $this->db->resultSet();
    }

    /**
     * Get tags for a specific resource
     * @param int $resourceId Resource ID
     * @return array
     */
    public function getResourceTags($resourceId)
    {
        $this->db->query('SELECT t.* FROM resource_tags t
                         JOIN resource_tag_relations r ON t.id = r.tag_id
                         WHERE r.resource_id = :resource_id');
        $this->db->bind(':resource_id', $resourceId);
        return $this->db->resultSet();
    }

    /**
     * Get ratings for a resource
     * @param int $resourceId Resource ID
     * @return array
     */
    public function getRatings($resourceId)
    {
        $this->db->query('SELECT r.*, u.name, u.profile_image 
                         FROM resource_ratings r
                         JOIN users u ON r.user_id = u.id
                         WHERE r.resource_id = :resource_id
                         ORDER BY r.created_at DESC');
        $this->db->bind(':resource_id', $resourceId);
        return $this->db->resultSet();
    }

    /**
     * Check if user has rated a resource
     * @param int $userId User ID
     * @param int $resourceId Resource ID
     * @return bool
     */
    public function hasUserRated($userId, $resourceId)
    {
        $this->db->query('SELECT * FROM resource_ratings 
                         WHERE user_id = :user_id AND resource_id = :resource_id');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':resource_id', $resourceId);

        $result = $this->db->single();
        return !empty($result);
    }

    /**
     * Add rating to a resource
     * @param array $data Rating data
     * @return bool
     */
    public function addRating($data)
    {
        // Insert rating
        $this->db->query('INSERT INTO resource_ratings (resource_id, user_id, rating, comment) 
                         VALUES (:resource_id, :user_id, :rating, :comment)');
        $this->db->bind(':resource_id', $data['resource_id']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':rating', $data['rating']);
        $this->db->bind(':comment', $data['comment'] ?? null);

        // Update the resource's average rating if rating is added
        if ($this->db->execute()) {
            // Calculate new average rating
            $this->db->query('SELECT AVG(rating) as avg_rating FROM resource_ratings WHERE resource_id = :resource_id');
            $this->db->bind(':resource_id', $data['resource_id']);
            $result = $this->db->single();

            // Update resource
            $this->db->query('UPDATE resources SET avg_rating = :avg_rating, 
                             rating_count = rating_count + 1 
                             WHERE id = :id');
            $this->db->bind(':avg_rating', round($result->avg_rating, 1));
            $this->db->bind(':id', $data['resource_id']);
            return $this->db->execute();
        }

        return false;
    }

    /**
     * Search resources
     * @param string $search Search query
     * @param string $sort Sort order (newest, popular, highest_rated, etc.)
     * @return array
     */
    public function searchResources($search, $sort = 'newest')
    {
        $searchTerm = "%{$search}%";

        $sortQuery = 'r.created_at DESC'; // Default sort by newest

        switch ($sort) {
            case 'popular':
                $sortQuery = 'r.view_count DESC';
                break;
            case 'downloads':
                $sortQuery = 'r.download_count DESC';
                break;
            case 'highest_rated':
                $sortQuery = 'r.avg_rating DESC';
                break;
            case 'alphabetical':
                $sortQuery = 'r.title ASC';
                break;
        }

        $this->db->query('SELECT r.*, c.name as category_name, u.name as author_name
                         FROM resources r
                         JOIN resource_categories c ON r.category_id = c.id
                         JOIN users u ON r.user_id = u.id
                         LEFT JOIN resource_tag_relations tr ON r.id = tr.resource_id
                         LEFT JOIN resource_tags t ON tr.tag_id = t.id
                         WHERE r.title LIKE :search 
                         OR r.description LIKE :search
                         OR r.content LIKE :search
                         OR c.name LIKE :search
                         OR t.name LIKE :search
                         GROUP BY r.id
                         ORDER BY ' . $sortQuery);

        $this->db->bind(':search', $searchTerm);
        return $this->db->resultSet();
    }

    /**
     * Get resources by user
     * @param int $userId User ID
     * @param int $limit Limit
     * @return array
     */
    public function getResourcesByUser($userId, $limit = 10)
    {
        $this->db->query('SELECT r.*, c.name as category_name 
                         FROM resources r
                         JOIN resource_categories c ON r.category_id = c.id
                         WHERE r.user_id = :user_id
                         ORDER BY r.created_at DESC
                         LIMIT :limit');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    /**
     * Delete a resource
     * @param int $id Resource ID
     * @return bool
     */
    public function deleteResource($id)
    {
        // Get resource details to check for files
        $resource = $this->getResourceById($id);

        if (!$resource) {
            return false;
        }

        // Start transaction
        $this->db->beginTransaction();

        // Delete tag relations
        $this->db->query('DELETE FROM resource_tag_relations WHERE resource_id = :id');
        $this->db->bind(':id', $id);
        $tagRelationsDeleted = $this->db->execute();

        // Delete ratings
        $this->db->query('DELETE FROM resource_ratings WHERE resource_id = :id');
        $this->db->bind(':id', $id);
        $ratingsDeleted = $this->db->execute();

        // Delete the resource
        $this->db->query('DELETE FROM resources WHERE id = :id');
        $this->db->bind(':id', $id);
        $resourceDeleted = $this->db->execute();

        // Commit or rollback based on results
        if ($tagRelationsDeleted && $ratingsDeleted && $resourceDeleted) {
            // Delete files if they exist
            if (!empty($resource->file_path)) {
                $filePath = APPROOT . '/../public/uploads/resource_files/' . $resource->file_path;
                if (file_exists($filePath)) {
                    unlink($filePath);
                }
            }

            if (!empty($resource->thumbnail)) {
                $thumbnailPath = APPROOT . '/../public/uploads/resource_thumbnails/' . $resource->thumbnail;
                if (file_exists($thumbnailPath)) {
                    unlink($thumbnailPath);
                }
            }

            $this->db->commit();
            return true;
        } else {
            $this->db->rollBack();
            return false;
        }
    }

    /**
     * Update a resource
     * @param array $data Resource data
     * @return bool
     */
    public function updateResource($data)
    {
        // Start transaction
        $this->db->beginTransaction();

        // Update resource
        $this->db->query('UPDATE resources SET 
                         title = :title, 
                         category_id = :category_id, 
                         resource_type = :resource_type,
                         description = :description, 
                         content = :content, 
                         external_link = :external_link
                         WHERE id = :id');

        $this->db->bind(':title', $data['title']);
        $this->db->bind(':category_id', $data['category_id']);
        $this->db->bind(':resource_type', $data['resource_type']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':content', $data['content'] ?? null);
        $this->db->bind(':external_link', $data['external_link'] ?? null);
        $this->db->bind(':id', $data['id']);

        $resourceUpdated = $this->db->execute();

        // Update file if present
        if (!empty($data['file_path'])) {
            $this->db->query('UPDATE resources SET file_path = :file_path WHERE id = :id');
            $this->db->bind(':file_path', $data['file_path']);
            $this->db->bind(':id', $data['id']);
            $resourceUpdated = $this->db->execute();
        }

        // Update thumbnail if present
        if (!empty($data['thumbnail'])) {
            $this->db->query('UPDATE resources SET thumbnail = :thumbnail WHERE id = :id');
            $this->db->bind(':thumbnail', $data['thumbnail']);
            $this->db->bind(':id', $data['id']);
            $resourceUpdated = $this->db->execute();
        }

        // Update tags if present
        if (isset($data['tags'])) {
            // Delete existing tag relations
            $this->db->query('DELETE FROM resource_tag_relations WHERE resource_id = :resource_id');
            $this->db->bind(':resource_id', $data['id']);
            $tagRelationsDeleted = $this->db->execute();

            // Add new tag relations
            $tagInsertSuccess = true;

            foreach ($data['tags'] as $tagId) {
                $this->db->query('INSERT INTO resource_tag_relations (resource_id, tag_id) 
                                 VALUES (:resource_id, :tag_id)');
                $this->db->bind(':resource_id', $data['id']);
                $this->db->bind(':tag_id', $tagId);

                if (!$this->db->execute()) {
                    $tagInsertSuccess = false;
                }
            }

            // If tag insertion fails, rollback
            if (!$tagInsertSuccess) {
                $this->db->rollBack();
                return false;
            }
        }

        // All operations successful, commit
        if ($resourceUpdated) {
            $this->db->commit();
            return true;
        } else {
            // Update failed, rollback
            $this->db->rollBack();
            return false;
        }
    }
}
