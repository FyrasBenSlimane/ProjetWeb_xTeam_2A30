<?php
class Job {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    /**
     * Get all active jobs
     * 
     * @return array Jobs data
     */
    public function getAllJobs() {
        $this->db->query("SELECT 
                j.*, 
                u.name as client_name,
                u.profile_image
            FROM jobs j
            LEFT JOIN users u ON j.user_id = u.id
            WHERE j.status = 'active'
            ORDER BY j.created_at DESC");
        
        return $this->db->resultSet();
    }

    /**
     * Get jobs with optional filters
     * 
     * @param array $filters Array of filter criteria
     * @return array Jobs data
     */
    public function getFilteredJobs($filters = []) {
        $sql = "SELECT 
                j.*, 
                u.name as client_name,
                u.profile_image
            FROM jobs j
            LEFT JOIN users u ON j.user_id = u.id
            WHERE j.status = 'active'";
        
        // Add search filter if provided
        if (isset($filters['search']) && !empty($filters['search'])) {
            $search = $filters['search'];
            $sql .= " AND (j.title LIKE :search OR j.description LIKE :search)";
        }
        
        // Add category filter if provided
        if (isset($filters['category']) && !empty($filters['category'])) {
            $sql .= " AND j.category = :category";
        }
        
        // Add job type filter if provided
        if (isset($filters['job_type']) && !empty($filters['job_type'])) {
            $sql .= " AND j.job_type = :job_type";
        }
        
        // Add experience level filter if provided
        if (isset($filters['experience_level']) && !empty($filters['experience_level'])) {
            $sql .= " AND j.experience_level = :experience_level";
        }
        
        // Add budget range filter if provided
        if (isset($filters['min_budget']) && is_numeric($filters['min_budget'])) {
            $sql .= " AND j.budget >= :min_budget";
        }
        
        if (isset($filters['max_budget']) && is_numeric($filters['max_budget'])) {
            $sql .= " AND j.budget <= :max_budget";
        }
        
        // Add skills filter if provided
        if (isset($filters['skills']) && !empty($filters['skills'])) {
            // For JSON columns, use JSON_CONTAINS or similar MySQL function
            // Example: skills field is JSON array
            if (is_array($filters['skills'])) {
                foreach ($filters['skills'] as $index => $skill) {
                    $sql .= " AND JSON_CONTAINS(j.skills, :skill{$index}, '$')";
                }
            }
        }
        
        // Add sorting
        $sort = isset($filters['sort']) ? $filters['sort'] : 'newest';
        switch ($sort) {
            case 'oldest':
                $sql .= " ORDER BY j.created_at ASC";
                break;
            case 'budget_high':
                $sql .= " ORDER BY j.budget DESC";
                break;
            case 'budget_low':
                $sql .= " ORDER BY j.budget ASC";
                break;
            case 'newest':
            default:
                $sql .= " ORDER BY j.created_at DESC";
                break;
        }
        
        // Add limit if specified
        if (isset($filters['limit']) && is_numeric($filters['limit'])) {
            $sql .= " LIMIT :limit";
            if (isset($filters['offset']) && is_numeric($filters['offset'])) {
                $sql .= " OFFSET :offset";
            }
        }
        
        $this->db->query($sql);
        
        // Bind parameters
        if (isset($filters['search']) && !empty($filters['search'])) {
            $this->db->bind(':search', '%' . $filters['search'] . '%');
        }
        
        if (isset($filters['category']) && !empty($filters['category'])) {
            $this->db->bind(':category', $filters['category']);
        }
        
        if (isset($filters['job_type']) && !empty($filters['job_type'])) {
            $this->db->bind(':job_type', $filters['job_type']);
        }
        
        if (isset($filters['experience_level']) && !empty($filters['experience_level'])) {
            $this->db->bind(':experience_level', $filters['experience_level']);
        }
        
        if (isset($filters['min_budget']) && is_numeric($filters['min_budget'])) {
            $this->db->bind(':min_budget', $filters['min_budget']);
        }
        
        if (isset($filters['max_budget']) && is_numeric($filters['max_budget'])) {
            $this->db->bind(':max_budget', $filters['max_budget']);
        }
        
        if (isset($filters['skills']) && !empty($filters['skills']) && is_array($filters['skills'])) {
            foreach ($filters['skills'] as $index => $skill) {
                $this->db->bind(":skill{$index}", json_encode($skill));
            }
        }
        
        if (isset($filters['limit']) && is_numeric($filters['limit'])) {
            $this->db->bind(':limit', $filters['limit']);
            if (isset($filters['offset']) && is_numeric($filters['offset'])) {
                $this->db->bind(':offset', $filters['offset']);
            }
        }
        
        return $this->db->resultSet();
    }

    /**
     * Get a single job by ID
     * 
     * @param int $id Job ID
     * @return object Job data
     */
    public function getJobById($id) {
        $this->db->query("SELECT 
                j.*, 
                u.name as client_name,
                u.profile_image
            FROM jobs j
            LEFT JOIN users u ON j.user_id = u.id
            WHERE j.id = :id");
        
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }

    /**
     * Check if a job is saved by a user
     * 
     * @param int $jobId Job ID
     * @param int $userId User ID
     * @return bool True if saved, false otherwise
     */
    public function isJobSaved($jobId, $userId) {
        $this->db->query("SELECT COUNT(*) as count FROM saved_jobs WHERE job_id = :job_id AND user_id = :user_id");
        $this->db->bind(':job_id', $jobId);
        $this->db->bind(':user_id', $userId);
        
        $row = $this->db->single();
        return $row->count > 0;
    }

    /**
     * Get total saved jobs count for a user
     * 
     * @param int $userId User ID
     * @return int Count of saved jobs
     */
    public function getSavedJobsCount($userId) {
        $this->db->query("SELECT COUNT(*) as count FROM saved_jobs WHERE user_id = :user_id");
        $this->db->bind(':user_id', $userId);
        
        $row = $this->db->single();
        return $row->count;
    }

    /**
     * Format skills from JSON to array
     * 
     * @param string $skillsJson JSON string of skills
     * @return array Array of skill strings
     */
    public function formatSkills($skillsJson) {
        if (empty($skillsJson)) {
            return [];
        }
        
        // Decode JSON string to PHP array
        return json_decode($skillsJson, true);
    }

    /**
     * Format job post time to human-readable format
     * 
     * @param string $timestamp MySQL timestamp
     * @return string Human-readable time difference
     */
    public function getTimeAgo($timestamp) {
        $time = strtotime($timestamp);
        $now = time();
        $diff = $now - $time;
        
        if ($diff < 60) {
            return 'Just now';
        } elseif ($diff < 3600) {
            $mins = floor($diff / 60);
            return $mins . ' minute' . ($mins == 1 ? '' : 's') . ' ago';
        } elseif ($diff < 86400) {
            $hours = floor($diff / 3600);
            return $hours . ' hour' . ($hours == 1 ? '' : 's') . ' ago';
        } elseif ($diff < 604800) {
            $days = floor($diff / 86400);
            return $days . ' day' . ($days == 1 ? '' : 's') . ' ago';
        } elseif ($diff < 2592000) {
            $weeks = floor($diff / 604800);
            return $weeks . ' week' . ($weeks == 1 ? '' : 's') . ' ago';
        } elseif ($diff < 31536000) {
            $months = floor($diff / 2592000);
            return $months . ' month' . ($months == 1 ? '' : 's') . ' ago';
        } else {
            $years = floor($diff / 31536000);
            return $years . ' year' . ($years == 1 ? '' : 's') . ' ago';
        }
    }

    /**
     * Get jobs posted by a specific user
     * 
     * @param int $userId User ID
     * @return array Jobs data
     */
    public function getJobsByUserId($userId) {
        $this->db->query("SELECT 
                j.*, 
                (SELECT COUNT(*) FROM applications WHERE job_id = j.id) as application_count
            FROM jobs j
            WHERE j.user_id = :user_id
            ORDER BY j.created_at DESC");
        
        $this->db->bind(':user_id', $userId);
        
        $results = $this->db->resultSet();
        
        // Make sure we return an empty array instead of null if no jobs are found
        return !empty($results) ? $results : [];
    }
    
    /**
     * Create a new job
     * 
     * @param array $data Job data
     * @return bool True if successful, false otherwise
     */
    public function createJob($data) {
        $this->db->query("INSERT INTO jobs (
                user_id, 
                title, 
                description, 
                category, 
                skills, 
                budget, 
                job_type, 
                experience_level, 
                duration, 
                status, 
                created_at
            ) VALUES (
                :user_id, 
                :title, 
                :description, 
                :category, 
                :skills, 
                :budget, 
                :job_type, 
                :experience_level, 
                :duration, 
                :status, 
                NOW()
            )");
        
        // Bind values
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':skills', $data['skills']);
        $this->db->bind(':budget', $data['budget']);
        $this->db->bind(':job_type', $data['job_type']);
        $this->db->bind(':experience_level', $data['experience_level']);
        $this->db->bind(':duration', $data['duration']);
        $this->db->bind(':status', $data['status']);
        
        // Execute
        return $this->db->execute();
    }
    
    /**
     * Update job details
     * 
     * @param array $data Job data
     * @return bool True if successful, false otherwise
     */
    public function updateJob($data) {
        // Prepare SQL
        $this->db->query('UPDATE jobs SET 
                        title = :title, 
                        description = :description, 
                        skills = :skills, 
                        budget = :budget, 
                        job_type = :job_type, 
                        experience_level = :experience_level, 
                        duration = :duration, 
                        updated_at = NOW() 
                        WHERE id = :id AND user_id = :user_id');
        
        // Bind values
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':user_id', $_SESSION['user_id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':skills', $data['skills']);
        $this->db->bind(':budget', $data['budget']);
        $this->db->bind(':job_type', $data['job_type']);
        $this->db->bind(':experience_level', $data['experience_level']);
        $this->db->bind(':duration', $data['duration']);
        
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Get applications for a job
     * 
     * @param int $jobId Job ID
     * @return array Applications data
     */
    public function getJobApplications($jobId) {
        $this->db->query("SELECT 
                a.*,
                u.name as freelancer_name,
                u.profile_image,
                u.hourly_rate,
                0 as avg_rating
            FROM applications a
            LEFT JOIN users u ON a.freelancer_id = u.id
            WHERE a.job_id = :job_id
            ORDER BY a.created_at DESC");
        
        $this->db->bind(':job_id', $jobId);
        
        return $this->db->resultSet();
    }
    
    /**
     * Change job status
     * 
     * @param int $jobId Job ID
     * @param string $status New status
     * @param int $userId User ID (owner)
     * @return bool True if successful, false otherwise
     */
    public function changeJobStatus($jobId, $status, $userId) {
        $this->db->query("UPDATE jobs SET status = :status, updated_at = NOW() 
                          WHERE id = :id AND user_id = :user_id");
        
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $jobId);
        $this->db->bind(':user_id', $userId);
        
        return $this->db->execute();
    }

    /**
     * Get a single application by ID
     * 
     * @param int $applicationId Application ID
     * @return object|false Application data or false if not found
     */
    public function getApplicationById($applicationId) {
        $this->db->query("SELECT 
                a.*,
                u.name as freelancer_name,
                u.profile_image,
                u.hourly_rate,
                0 as avg_rating
            FROM applications a
            LEFT JOIN users u ON a.freelancer_id = u.id
            WHERE a.id = :application_id");
        
        $this->db->bind(':application_id', $applicationId);
        
        return $this->db->single();
    }

    /**
     * Update job status (active, paused, closed)
     *
     * @param int $jobId Job ID
     * @param string $status New status
     * @return bool Success/failure
     */
    public function updateJobStatus($jobId, $status) {
        // Validate status
        if (!in_array($status, ['active', 'paused', 'closed'])) {
            return false;
        }
        
        // Prepare SQL
        $this->db->query('UPDATE jobs SET 
                        status = :status, 
                        updated_at = NOW() 
                        WHERE id = :id AND user_id = :user_id');
        
        // Bind values
        $this->db->bind(':id', $jobId);
        $this->db->bind(':user_id', $_SESSION['user_id']);
        $this->db->bind(':status', $status);
        
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    /**
     * Delete a job
     *
     * @param int $jobId Job ID
     * @return bool Success/failure
     */
    public function deleteJob($jobId) {
        // Prepare SQL
        $this->db->query('DELETE FROM jobs WHERE id = :id AND user_id = :user_id');
        
        // Bind values
        $this->db->bind(':id', $jobId);
        $this->db->bind(':user_id', $_SESSION['user_id']);
        
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Update a single field for a job
     * 
     * @param int $jobId The job ID
     * @param string $field The field name to update
     * @param mixed $value The new value
     * @return bool True if successful, false otherwise
     */
    public function updateJobField($jobId, $field, $value) {
        // Validate field name to prevent SQL injection
        $allowedFields = [
            'title', 'description', 'category', 'budget', 
            'skills', 'duration', 'status', 'experience_level'
        ];
        
        if (!in_array($field, $allowedFields)) {
            return false;
        }
        
        // Prepare SQL
        $sql = "UPDATE jobs SET $field = :value, updated_at = NOW() 
                WHERE id = :id AND user_id = :user_id";
        
        $this->db->query($sql);
        
        // Bind values
        $this->db->bind(':value', $value);
        $this->db->bind(':id', $jobId);
        $this->db->bind(':user_id', $_SESSION['user_id']);
        
        // Execute
        return $this->db->execute();
    }

    /**
     * Get unique categories from jobs table
     * 
     * @return array List of unique categories
     */
    public function getUniqueCategories() {
        $this->db->query("SELECT DISTINCT category FROM jobs WHERE status = 'active' ORDER BY category");
        
        $results = $this->db->resultSet();
        
        // Extract category names from result objects
        $categories = [];
        foreach ($results as $result) {
            $categories[] = $result->category;
        }
        
        return $categories;
    }
    
    /**
     * Search for jobs based on a query string
     * 
     * @param string $query Search query
     * @param array $filters Additional filters
     * @return array Job results
     */
    public function searchJobs($query, $filters = []) {
        // Base SQL query without FULLTEXT search
        $sql = "SELECT 
                j.*, 
                u.name as client_name,
                u.profile_image
            FROM jobs j
            LEFT JOIN users u ON j.user_id = u.id
            WHERE j.status = 'active'";
        
        // Add search condition
        if (!empty($query)) {
            // Use LIKE instead of FULLTEXT
            $sql .= " AND (
                j.title LIKE :query_like 
                OR j.description LIKE :query_like 
                OR JSON_CONTAINS(j.skills, JSON_ARRAY(:query_exact), '$')
            )";
        }
        
        // Add category filter if provided
        if (isset($filters['category']) && !empty($filters['category'])) {
            $sql .= " AND j.category = :category";
        }
        
        // Add job type filter if provided
        if (isset($filters['job_type']) && !empty($filters['job_type'])) {
            $sql .= " AND j.job_type = :job_type";
        }
        
        // Add experience level filter if provided
        if (isset($filters['experience_level']) && !empty($filters['experience_level'])) {
            $sql .= " AND j.experience_level = :experience_level";
        }
        
        // Add budget range filters
        if (isset($filters['min_budget']) && is_numeric($filters['min_budget'])) {
            $sql .= " AND j.budget >= :min_budget";
        }
        
        if (isset($filters['max_budget']) && is_numeric($filters['max_budget'])) {
            $sql .= " AND j.budget <= :max_budget";
        }
        
        // Add sorting
        $sort = isset($filters['sort']) ? $filters['sort'] : 'newest';
        switch ($sort) {
            case 'budget_high':
                $sql .= " ORDER BY j.budget DESC";
                break;
            case 'budget_low':
                $sql .= " ORDER BY j.budget ASC";
                break;
            case 'newest':
            default:
                $sql .= " ORDER BY j.created_at DESC";
                break;
        }
        
        // Add limit if specified
        if (isset($filters['limit']) && is_numeric($filters['limit'])) {
            $sql .= " LIMIT :limit";
            if (isset($filters['offset']) && is_numeric($filters['offset'])) {
                $sql .= " OFFSET :offset";
            }
        }
        
        $this->db->query($sql);
        
        // Bind search parameters - only if query is not empty
        if (!empty($query)) {
            $this->db->bind(':query_like', '%' . $query . '%');
            $this->db->bind(':query_exact', $query);
        }
        
        // Bind filter parameters
        if (isset($filters['category']) && !empty($filters['category'])) {
            $this->db->bind(':category', $filters['category']);
        }
        
        if (isset($filters['job_type']) && !empty($filters['job_type'])) {
            $this->db->bind(':job_type', $filters['job_type']);
        }
        
        if (isset($filters['experience_level']) && !empty($filters['experience_level'])) {
            $this->db->bind(':experience_level', $filters['experience_level']);
        }
        
        if (isset($filters['min_budget']) && is_numeric($filters['min_budget'])) {
            $this->db->bind(':min_budget', $filters['min_budget']);
        }
        
        if (isset($filters['max_budget']) && is_numeric($filters['max_budget'])) {
            $this->db->bind(':max_budget', $filters['max_budget']);
        }
        
        if (isset($filters['limit']) && is_numeric($filters['limit'])) {
            $this->db->bind(':limit', $filters['limit']);
            if (isset($filters['offset']) && is_numeric($filters['offset'])) {
                $this->db->bind(':offset', $filters['offset']);
            }
        }
        
        return $this->db->resultSet();
    }
    
    /**
     * Search for completed projects
     * 
     * @param string $query Search query
     * @param array $filters Additional filters
     * @return array Project results
     */
    public function searchProjects($query, $filters = []) {
        // For projects, we filter for completed jobs
        $filters['status'] = 'completed';
        
        // Base SQL query
        $sql = "SELECT 
                j.*, 
                u.name as client_name,
                u.profile_image
            FROM jobs j
            LEFT JOIN users u ON j.user_id = u.id
            WHERE j.status = 'completed'";
        
        // Add search condition
        if (!empty($query)) {
            $sql .= " AND (
                j.title LIKE :query_like 
                OR j.description LIKE :query_like 
                OR JSON_CONTAINS(j.skills, JSON_ARRAY(:query_exact), '$')
            )";
        }
        
        // Add filters
        if (isset($filters['category']) && !empty($filters['category'])) {
            $sql .= " AND j.category = :category";
        }
        
        // Add sorting
        $sort = isset($filters['sort']) ? $filters['sort'] : 'newest';
        switch ($sort) {
            case 'budget_high':
                $sql .= " ORDER BY j.budget DESC";
                break;
            case 'budget_low':
                $sql .= " ORDER BY j.budget ASC";
                break;
            case 'newest':
            default:
                $sql .= " ORDER BY j.created_at DESC";
                break;
        }
        
        // Add limit if specified
        if (isset($filters['limit']) && is_numeric($filters['limit'])) {
            $sql .= " LIMIT :limit";
            if (isset($filters['offset']) && is_numeric($filters['offset'])) {
                $sql .= " OFFSET :offset";
            }
        }
        
        $this->db->query($sql);
        
        // Bind search parameters - only if query is not empty
        if (!empty($query)) {
            $this->db->bind(':query_like', '%' . $query . '%');
            $this->db->bind(':query_exact', $query);
        }
        
        // Bind filter parameters
        if (isset($filters['category']) && !empty($filters['category'])) {
            $this->db->bind(':category', $filters['category']);
        }
        
        if (isset($filters['limit']) && is_numeric($filters['limit'])) {
            $this->db->bind(':limit', $filters['limit']);
            if (isset($filters['offset']) && is_numeric($filters['offset'])) {
                $this->db->bind(':offset', $filters['offset']);
            }
        }
        
        return $this->db->resultSet();
    }


    /**
     * Apply for a job - creates a new application
     * 
     * @param int $jobId Job ID
     * @param int $freelancerId Freelancer ID
     * @param string $coverLetter Cover letter/proposal text
     * @param float $bid Bid amount (optional)
     * @return bool|int Returns the application ID if successful, false otherwise
     */
    public function applyForJob($jobId, $freelancerId, $coverLetter, $bid = null) {
        // Check if user has already applied for this job
        $this->db->query("SELECT COUNT(*) as count FROM applications 
                        WHERE job_id = :job_id AND freelancer_id = :freelancer_id");
        $this->db->bind(':job_id', $jobId);
        $this->db->bind(':freelancer_id', $freelancerId);
        
        $result = $this->db->single();
        if ($result->count > 0) {
            // Already applied
            return false;
        }
        
        // Create the application
        $this->db->query("INSERT INTO applications (job_id, freelancer_id, cover_letter, bid_amount, status, created_at) 
                        VALUES (:job_id, :freelancer_id, :cover_letter, :bid_amount, 'pending', NOW())");
        
        $this->db->bind(':job_id', $jobId);
        $this->db->bind(':freelancer_id', $freelancerId);
        $this->db->bind(':cover_letter', $coverLetter);
        $this->db->bind(':bid_amount', $bid);
        
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }
} 