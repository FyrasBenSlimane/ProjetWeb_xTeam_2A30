<?php
class User {
    private $db;
    
    public function __construct() {
        $this->db = new Database;
    }
    
    // Register new user
    public function register($data) {
        // Prepare statement with all the fields from our consolidated schema
        $this->db->query('INSERT INTO users (
            name, 
            email, 
            password, 
            account_type, 
            country, 
            terms_accepted, 
            skills, 
            education, 
            work_history, 
            portfolio, 
            languages, 
            visibility, 
            project_preference, 
            professional_title, 
            status
        ) VALUES (
            :name, 
            :email, 
            :password, 
            :account_type, 
            :country, 
            :terms_accepted, 
            :skills, 
            :education, 
            :work_history, 
            :portfolio, 
            :languages, 
            :visibility, 
            :project_preference, 
            :professional_title, 
            :status
        )');
        
        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':account_type', $data['account_type']);
        $this->db->bind(':country', $data['country'] ?? null);
        $this->db->bind(':terms_accepted', $data['terms_accepted'] ?? false);
        $this->db->bind(':skills', $data['skills'] ?? json_encode([]));
        $this->db->bind(':education', $data['education'] ?? json_encode([]));
        $this->db->bind(':work_history', $data['work_history'] ?? json_encode([]));
        $this->db->bind(':portfolio', $data['portfolio'] ?? json_encode([]));
        $this->db->bind(':languages', $data['languages'] ?? json_encode([]));
        $this->db->bind(':visibility', $data['visibility'] ?? 'public');
        $this->db->bind(':project_preference', $data['project_preference'] ?? 'both');
        $this->db->bind(':professional_title', $data['professional_title'] ?? '');
        $this->db->bind(':status', 'active'); // Default status is active
        
        // Execute
        if($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }
    
    // Login user and check status
    public function login($email, $password) {
        // First check if user exists and password is correct, regardless of status
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        
        $row = $this->db->single();
        
        if($row) {
            $hashed_password = $row->password;
            if(password_verify($password, $hashed_password)) {
                return $row; // Return user even if inactive - status will be checked in controller
            }
        }
        
        return false;
    }
    
    // Find user by email
    public function findUserByEmail($email) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        
        $row = $this->db->single();
        
        // Check if row exists and return the user object if found
        if($this->db->rowCount() > 0) {
            return $row; // Return the user object instead of just true
        } else {
            return false;
        }
    }
    
    // Get user by ID
    public function getUserById($id) {
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }
    
    /**
     * Get client information for job details
     * 
     * @param int $userId User ID
     * @return object Client information
     */
    public function getClientInfo($userId) {
        $this->db->query('SELECT 
            u.name, 
            u.country as location, 
            u.created_at as member_since,
            u.profile_image,
            (SELECT COUNT(*) FROM jobs WHERE user_id = :user_id) as jobs_posted
        FROM users u
        WHERE u.id = :user_id3');
        
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':user_id3', $userId);
        
        $clientInfo = $this->db->single();
        
        // Format member since date
        if ($clientInfo && $clientInfo->member_since) {
            $clientInfo->member_since = date('F Y', strtotime($clientInfo->member_since));
        }
        
        // Set default total spent since we removed that query
        $clientInfo->total_spent = '0.00';
        
        return $clientInfo;
    }
    
    // Update user profile
    public function updateProfile($data) {
        // Prepare statement
        $this->db->query('UPDATE users SET 
                         name = :name, 
                         bio = :bio, 
                         location = :location 
                         WHERE id = :user_id');
        
        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':bio', $data['bio']);
        $this->db->bind(':location', $data['location']);
        $this->db->bind(':user_id', $data['user_id']);
        
        // Add hourly rate if provided (for freelancers)
        if (isset($data['hourly_rate'])) {
            $this->db->query('UPDATE users SET 
                             hourly_rate = :hourly_rate 
                             WHERE id = :user_id');
            $this->db->bind(':hourly_rate', $data['hourly_rate']);
            $this->db->bind(':user_id', $data['user_id']);
            $this->db->execute();
        }
        
        // Execute main update
        return $this->db->execute();
    }
    
    // Update user skills
    public function updateSkills($data) {
        // Convert skills array to JSON
        $skillsJson = json_encode($data['skills']);
        
        // Prepare statement
        $this->db->query('UPDATE users SET skills = :skills WHERE id = :user_id');
        
        // Bind values
        $this->db->bind(':skills', $skillsJson);
        $this->db->bind(':user_id', $data['user_id']);
        
        // Execute
        return $this->db->execute();
    }
    
    // Update user social links
    public function updateSocialLinks($data) {
        // Prepare statement
        $this->db->query('UPDATE users SET 
                         website = :website, 
                         linkedin = :linkedin, 
                         github = :github, 
                         twitter = :twitter 
                         WHERE id = :user_id');
        
        // Bind values
        $this->db->bind(':website', $data['website']);
        $this->db->bind(':linkedin', $data['linkedin']);
        $this->db->bind(':github', $data['github']);
        $this->db->bind(':twitter', $data['twitter']);
        $this->db->bind(':user_id', $data['user_id']);
        
        // Execute
        return $this->db->execute();
    }
    
    // Update user avatar
    public function updateAvatar($data) {
        // Prepare statement
        $this->db->query('UPDATE users SET profile_image = :avatar WHERE id = :user_id');
        
        // Bind values
        $this->db->bind(':avatar', $data['avatar']);
        $this->db->bind(':user_id', $data['user_id']);
        
        // Execute
        return $this->db->execute();
    }
    
    // Update user account information
    public function updateAccount($data) {
        // Prepare statement
        $this->db->query('UPDATE users SET 
                         name = :name, 
                         email = :email,
                         country = :country
                         WHERE id = :user_id');
        
        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':country', $data['country']);
        $this->db->bind(':user_id', $data['user_id']);
        
        // Execute
        return $this->db->execute();
    }
    
    // Update user password
    public function updatePassword($data) {
        // Hash the new password
        $hashedPassword = password_hash($data['new_password'], PASSWORD_DEFAULT);
        
        // Prepare statement
        $this->db->query('UPDATE users SET password = :password WHERE id = :user_id');
        
        // Bind values
        $this->db->bind(':password', $hashedPassword);
        $this->db->bind(':user_id', $data['user_id']);
        
        // Execute
        return $this->db->execute();
    }
    
    /**
     * Update user notification preferences
     * 
     * @param array $data User notification preferences data
     * @return bool True on success, false on failure
     */
    public function updateNotificationPreferences($data) {
        // Prepare statement
        $this->db->query('UPDATE users SET 
                         email_updates = :email_updates, 
                         message_alerts = :message_alerts, 
                         job_recommendations = :job_recommendations, 
                         marketing_emails = :marketing_emails 
                         WHERE id = :user_id');
        
        // Bind values
        $this->db->bind(':email_updates', $data['email_updates']);
        $this->db->bind(':message_alerts', $data['message_alerts']);
        $this->db->bind(':job_recommendations', $data['job_recommendations']);
        $this->db->bind(':marketing_emails', $data['marketing_emails']);
        $this->db->bind(':user_id', $data['user_id']);
        
        // Execute
        return $this->db->execute();
    }
    
    /**
     * Update user preferences
     * 
     * @param array $data User preference data
     * @return bool True on success, false on failure
     */
    public function updatePreferences($data) {
        // Prepare statement
        $this->db->query('UPDATE users SET 
                         language = :language, 
                         timezone = :timezone, 
                         currency = :currency 
                         WHERE id = :user_id');
        
        // Bind values
        $this->db->bind(':language', $data['language']);
        $this->db->bind(':timezone', $data['timezone']);
        $this->db->bind(':currency', $data['currency']);
        $this->db->bind(':user_id', $data['user_id']);
        
        // Execute
        return $this->db->execute();
    }
    
    /**
     * Update privacy settings for a user
     * 
     * @param array $data User privacy data
     * @return bool True on success, false on failure
     */
    public function updatePrivacySettings($data) {
        $this->db->query('UPDATE users SET 
                         profile_visibility = :profile_visibility,
                         data_usage = :data_usage
                         WHERE id = :user_id');
        
        $this->db->bind(':profile_visibility', $data['profile_visibility']);
        $this->db->bind(':data_usage', $data['data_usage']);
        $this->db->bind(':user_id', $data['user_id']);
        
        return $this->db->execute();
    }

    /**
     * Deactivate a user account
     * Sets the status to 'inactive' and logs the reason
     * 
     * @param array $data User data and deactivation reason
     * @return bool True on success, false on failure
     */
    public function deactivateAccount($data) {
        // Start transaction
        $this->db->beginTransaction();
        
        try {
            // Update user status
            $this->db->query('UPDATE users SET status = :status WHERE id = :user_id');
            $this->db->bind(':status', 'inactive');
            $this->db->bind(':user_id', $data['user_id']);
            $this->db->execute();
            
            // Log deactivation reason if provided
            if (!empty($data['reason'])) {
                $this->db->query('INSERT INTO user_activity_log (user_id, activity_type, details, created_at) 
                                 VALUES (:user_id, :activity_type, :details, NOW())');
                $this->db->bind(':user_id', $data['user_id']);
                $this->db->bind(':activity_type', 'account_deactivated');
                $this->db->bind(':details', json_encode(['reason' => $data['reason']]));
                $this->db->execute();
            }
            
            // Commit transaction
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            // Rollback on error
            $this->db->rollback();
            error_log('Error deactivating account: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Permanently delete a user account and all associated data
     * This is a destructive operation and cannot be undone
     * 
     * @param int $userId The user ID to delete
     * @return bool True on success, false on failure
     */
    public function deleteAccount($userId) {
        // Start transaction
        $this->db->beginTransaction();
        
        try {
            // Delete associated data first
            
            // Delete portfolio items
            $this->db->query('DELETE FROM portfolio_items WHERE user_id = :user_id');
            $this->db->bind(':user_id', $userId);
            $this->db->execute();
            
            // Delete work history
            $this->db->query('DELETE FROM work_history WHERE user_id = :user_id');
            $this->db->bind(':user_id', $userId);
            $this->db->execute();
            
            // Delete education history
            $this->db->query('DELETE FROM education WHERE user_id = :user_id');
            $this->db->bind(':user_id', $userId);
            $this->db->execute();
            
            // Delete user settings
            $this->db->query('DELETE FROM user_settings WHERE user_id = :user_id');
            $this->db->bind(':user_id', $userId);
            $this->db->execute();
            
            // Delete user sessions
            $this->db->query('DELETE FROM user_sessions WHERE user_id = :user_id');
            $this->db->bind(':user_id', $userId);
            $this->db->execute();
            
            // Delete user activity logs
            $this->db->query('DELETE FROM user_activity_log WHERE user_id = :user_id');
            $this->db->bind(':user_id', $userId);
            $this->db->execute();
            
            // Delete job applications if this is a freelancer
            $this->db->query('DELETE FROM job_applications WHERE freelancer_id = :user_id');
            $this->db->bind(':user_id', $userId);
            $this->db->execute();
            
            // Delete jobs if this is a client
            $this->db->query('DELETE FROM jobs WHERE user_id = :user_id');
            $this->db->bind(':user_id', $userId);
            $this->db->execute();
            
            // Delete contracts
            $this->db->query('DELETE FROM contracts WHERE client_id = :user_id OR freelancer_id = :user_id2');
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':user_id2', $userId);
            $this->db->execute();
            
            // Delete messages
            $this->db->query('DELETE FROM messages WHERE sender_id = :user_id OR receiver_id = :user_id2');
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':user_id2', $userId);
            $this->db->execute();
            
            // Finally, delete the user account itself
            $this->db->query('DELETE FROM users WHERE id = :user_id');
            $this->db->bind(':user_id', $userId);
            $this->db->execute();
            
            // Commit transaction
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            // Rollback on error
            $this->db->rollback();
            error_log('Error deleting account: ' . $e->getMessage());
            return false;
        }
    }

    // Force user to change password
    public function setForcePasswordChange($id, $force = true) {
        $this->db->query('UPDATE users SET force_password_change = :force WHERE id = :id');
        $this->db->bind(':force', $force ? 1 : 0);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Check if user must change password
    public function mustChangePassword($id) {
        $this->db->query('SELECT force_password_change FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        $row = $this->db->single();
        return $row && $row->force_password_change == 1;
    }

    // Save login history
    public function saveLoginHistory($userId, $ip, $userAgent) {
        $this->db->query('INSERT INTO user_logins (user_id, ip_address, user_agent, login_time) VALUES (:user_id, :ip, :ua, NOW())');
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':ip', $ip);
        $this->db->bind(':ua', $userAgent);
        return $this->db->execute();
    }

    /**
     * Get total number of users with optional filtering
     * 
     * @param array $filters Optional filters (created_after, account_type, status)
     * @return int Number of users
     */
    public function getUserCount($filters = []) {
        // Start with a basic query
        $sql = 'SELECT COUNT(*) as count FROM users';
        
        // Add WHERE clause if there are filters
        $whereClause = [];
        $params = [];
        
        // Filter by creation date
        if (!empty($filters['created_after'])) {
            $whereClause[] = 'created_at >= :created_after';
            $params[':created_after'] = $filters['created_after'];
        }
        
        // Filter by account type
        if (!empty($filters['account_type'])) {
            $whereClause[] = 'account_type = :account_type';
            $params[':account_type'] = $filters['account_type'];
        }
        
        // Filter by status
        if (!empty($filters['status'])) {
            $whereClause[] = 'status = :status';
            $params[':status'] = $filters['status'];
        }
        
        // Add WHERE clause to SQL if we have conditions
        if (!empty($whereClause)) {
            $sql .= ' WHERE ' . implode(' AND ', $whereClause);
        }
        
        // Prepare the query
        $this->db->query($sql);
        
        // Bind parameters if any
        foreach ($params as $param => $value) {
            $this->db->bind($param, $value);
        }
        
        // Execute the query
        $result = $this->db->single();
        
        return $result ? $result->count : 0;
    }
    
    /**
     * Get recent login history
     * 
     * @param string $filter Time filter (today, week, month, all)
     * @param int $limit Number of records to return
     * @return array Array of login records
     */
    public function getRecentLogins($filter = 'all', $limit = 5) {
        // Determine date filter based on filter parameter
        $dateFilter = '';
        switch ($filter) {
            case 'today':
                $dateFilter = date('Y-m-d');
                break;
            case 'week':
                $dateFilter = date('Y-m-d', strtotime('-7 days'));
                break;
            case 'month':
                $dateFilter = date('Y-m-d', strtotime('-30 days'));
                break;
            default:
                // All time - no specific filter
                $dateFilter = '';
        }
        
        // Build the SQL query
        $sql = 'SELECT l.*, u.name 
                FROM user_logins l
                JOIN users u ON l.user_id = u.id';
        
        // Add WHERE clause if filter is set
        if (!empty($dateFilter)) {
            $sql .= ' WHERE DATE(l.login_time) >= :date_filter';
        }
        
        // Add ORDER BY and LIMIT
        $sql .= ' ORDER BY l.login_time DESC LIMIT :limit';
        
        // Prepare the query
        $this->db->query($sql);
        
        // Bind parameters
        if (!empty($dateFilter)) {
            $this->db->bind(':date_filter', $dateFilter);
        }
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        
        // Execute and return results
        $result = $this->db->resultSet();
        
        return $result ?: [];
    }
    
    /**
     * Get all users with optional filtering and pagination
     * 
     * @param array $filters Optional filters (search, account_type, status)
     * @param int $limit Number of records to return (0 for all)
     * @param int $offset Offset for pagination
     * @return array Array of user objects
     */
    public function getUsers($filters = [], $limit = 0, $offset = 0) {
        // Start with a basic query
        $sql = 'SELECT * FROM users';
        
        // Add WHERE clause if there are filters
        $whereClause = [];
        $params = [];
        
        // Filter by search term (matches name or email)
        if (!empty($filters['search'])) {
            $whereClause[] = '(name LIKE :search OR email LIKE :search)';
            $params[':search'] = '%' . $filters['search'] . '%';
        }
        
        // Filter by account type
        if (!empty($filters['account_type'])) {
            $whereClause[] = 'account_type = :account_type';
            $params[':account_type'] = $filters['account_type'];
        }
        
        // Filter by status
        if (!empty($filters['status'])) {
            $whereClause[] = 'status = :status';
            $params[':status'] = $filters['status'];
        }
        
        // Add WHERE clause to SQL if we have conditions
        if (!empty($whereClause)) {
            $sql .= ' WHERE ' . implode(' AND ', $whereClause);
        }
        
        // Add ORDER BY
        $sql .= ' ORDER BY id DESC';
        
        // Add LIMIT if specified
        if ($limit > 0) {
            $sql .= ' LIMIT :offset, :limit';
        }
        
        // Prepare the query
        $this->db->query($sql);
        
        // Bind parameters if any
        foreach ($params as $param => $value) {
            $this->db->bind($param, $value);
        }
        
        // Bind limit and offset if specified
        if ($limit > 0) {
            $this->db->bind(':limit', $limit, PDO::PARAM_INT);
            $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        }
        
        // Execute the query
        return $this->db->resultSet();
    }
    
    /**
     * Add a new user (admin function)
     * 
     * @param array $data User data (name, email, password, account_type, status)
     * @return int|bool User ID if successful, false otherwise
     */
    public function addUser($data) {
        // Prepare statement
        $this->db->query('INSERT INTO users (name, email, password, account_type, status, created_at) 
                          VALUES (:name, :email, :password, :account_type, :status, NOW())');
        
        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':account_type', $data['type']);
        $this->db->bind(':status', $data['status']);
        
        // Execute
        if($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }
    
    /**
     * Update an existing user (admin function)
     * 
     * @param array $data User data (id, name, email, password, account_type, status)
     * @return bool True if successful, false otherwise
     */
    public function updateUser($data) {
        // Check if $data is an array
        if (!is_array($data)) {
            // If not an array, treat it as a user ID and only update last_active
            $this->db->query('UPDATE users SET last_active = NOW(), updated_at = NOW() WHERE id = :id');
            $this->db->bind(':id', $data);
            return $this->db->execute();
        }

        // For backward compatibility with callers that only pass id and one or two fields
        $name = $data['name'] ?? '';
        $email = $data['email'] ?? '';
        $type = $data['type'] ?? $data['account_type'] ?? 'client';
        $status = $data['status'] ?? 'active';
        $id = $data['id'] ?? 0;

        // If we have google_id but no other data, just update that field
        if (isset($data['google_id']) && !isset($data['password']) && empty($name) && empty($email)) {
            $this->db->query('UPDATE users SET google_id = :google_id, updated_at = NOW() WHERE id = :id');
            $this->db->bind(':google_id', $data['google_id']);
            $this->db->bind(':id', $id);
            return $this->db->execute();
        }

        // Standard update based on available fields
        if(!empty($data['password'])) {
            // Update with password
            $this->db->query('UPDATE users SET name = :name, email = :email, 
                             password = :password, account_type = :account_type, 
                             status = :status, updated_at = NOW() 
                             WHERE id = :id');
            $this->db->bind(':password', $data['password']);
        } else {
            // Update without changing password
            $this->db->query('UPDATE users SET name = :name, email = :email, 
                             account_type = :account_type, status = :status, 
                             updated_at = NOW() 
                             WHERE id = :id');
        }
        
        // Bind remaining values
        $this->db->bind(':name', $name);
        $this->db->bind(':email', $email);
        $this->db->bind(':account_type', $type);
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        
        // Execute
        return $this->db->execute();
    }
    
    /**
     * Delete a user (admin function)
     * 
     * @param int $id User ID to delete
     * @return bool True if successful, false otherwise
     */
    public function deleteUser($id) {
        $this->db->query('DELETE FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    // Get all users
    public function getAllUsers() {
        $this->db->query('SELECT * FROM users ORDER BY created_at DESC');
        return $this->db->resultSet();
    }
    
    // Filter users by role and/or search query
    public function filterUsers($role = 'all', $search = '') {
        $sql = 'SELECT * FROM users WHERE 1=1';
        
        // Add role filter if not 'all'
        if ($role !== 'all') {
            $sql .= ' AND account_type = :role';
        }
        
        // Add search condition if search term provided
        if (!empty($search)) {
            $sql .= ' AND (name LIKE :search OR email LIKE :search)';
        }
        
        // Order by created date
        $sql .= ' ORDER BY created_at DESC';
        
        $this->db->query($sql);
        
        // Bind parameters
        if ($role !== 'all') {
            $this->db->bind(':role', $role);
        }
        
        if (!empty($search)) {
            $this->db->bind(':search', '%' . $search . '%');
        }
        
        return $this->db->resultSet();
    }

    /**
     * Get currently logged in user data
     * 
     * @return object|bool User data or false if not logged in
     */
    public function getCurrentUser() {
        if (isset($_SESSION['user_id'])) {
            return $this->getUserProfile($_SESSION['user_id']);
        }
        return false;
    }
    
    // Get user profile data - enhanced with detailed profile information
    public function getUserProfile($userId) {
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $userId);
        
        $profile = $this->db->single();
        
        if ($profile) {
            // If education, work_history, etc. are JSON fields, decode them
            if (isset($profile->education) && !empty($profile->education)) {
                $profile->education = json_decode($profile->education);
            }
            if (isset($profile->work_history) && !empty($profile->work_history)) {
                $profile->work_history = json_decode($profile->work_history);
            }
            if (isset($profile->skills) && !empty($profile->skills)) {
                $profile->skills = json_decode($profile->skills);
            }
            if (isset($profile->certifications) && !empty($profile->certifications)) {
                $profile->certifications = json_decode($profile->certifications);
            }
            if (isset($profile->portfolio) && !empty($profile->portfolio)) {
                $profile->portfolio = json_decode($profile->portfolio);
            }
            if (isset($profile->languages) && !empty($profile->languages)) {
                $profile->languages = json_decode($profile->languages);
            }
            
            return $profile;
        }
        
        return false;
    }
    
    // Check if a user has a profile (always true in new schema since profile is in users table)
    public function profileExists($userId) {
        $this->db->query('SELECT id FROM users WHERE id = :id');
        $this->db->bind(':id', $userId);
        return ($this->db->rowCount() > 0);
    }

    // Create or update user profile
    public function updateUserProfile($data) {
        // Create SQL query - we're updating the users table directly now
        $this->db->query('UPDATE users SET 
            bio = :bio,
            location = :location, 
            professional_title = :professional_title,
            experience_level = :experience_level,
            hourly_rate = :hourly_rate,
            profile_visibility = :visibility,
            project_preference = :project_preference,
            categories = :categories
            WHERE id = :user_id');
        
        // Bind values
        $this->db->bind(':bio', $data['bio']);
        $this->db->bind(':location', $data['location']);
        $this->db->bind(':professional_title', $data['professional_title']);
        $this->db->bind(':experience_level', $data['experience_level']);
        $this->db->bind(':hourly_rate', $data['hourly_rate']);
        $this->db->bind(':visibility', $data['profile_visibility'] ?? 'public');
        $this->db->bind(':project_preference', $data['project_preference']);
        $this->db->bind(':categories', $data['categories']);
        $this->db->bind(':user_id', $data['user_id']);
        
        // Execute
        if ($this->db->execute()) {
            return true;
        }
        
        return false;
    }

    // Update user basic info
    public function updateUserBasicInfo($data) {
        // Update the basic information in the users table
        $this->db->query('UPDATE users SET 
                          name = :name,
                          email = :email,
                          country = :country,
                          bio = :bio,
                          skills = :skills,
                          updated_at = NOW()
                          WHERE id = :id');
        
        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':country', $data['country'] ?? null);
        $this->db->bind(':bio', $data['bio'] ?? null);
        $this->db->bind(':skills', $data['skills'] ?? null);
        $this->db->bind(':id', $data['id']);
        
        // Execute
        return $this->db->execute();
    }

    // Update user profile image
    public function updateProfileImage($userId, $imagePath) {
        $this->db->query('UPDATE users SET profile_image = :profile_image WHERE id = :id');
        $this->db->bind(':profile_image', $imagePath);
        $this->db->bind(':id', $userId);
        return $this->db->execute();
    }
    
    // Get user portfolio items
    public function getUserPortfolio($userId) {
        $this->db->query('SELECT portfolio FROM users WHERE id = :user_id');
        $this->db->bind(':user_id', $userId);
        
        $result = $this->db->single();
        
        if ($result && isset($result->portfolio) && !empty($result->portfolio)) {
            return json_decode($result->portfolio);
        }
        
        return [];
    }
    
    // Add portfolio item
    public function addPortfolioItem($data) {
        // First get current portfolio array
        $currentPortfolio = $this->getUserPortfolio($data['user_id']);
        
        // Create new portfolio item
        $newPortfolioItem = [
            'id' => uniqid(), // Generate a unique ID
            'title' => $data['title'],
            'description' => $data['description'] ?? '',
            'image_url' => $data['image_url'] ?? '',
            'project_url' => $data['project_url'] ?? '',
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        // Add to array
        $currentPortfolio[] = $newPortfolioItem;
        
        // Update the user's portfolio JSON
        $this->db->query('UPDATE users SET portfolio = :portfolio WHERE id = :user_id');
        $this->db->bind(':portfolio', json_encode($currentPortfolio));
        $this->db->bind(':user_id', $data['user_id']);
        
        return $this->db->execute();
    }
    
    // Update portfolio item
    public function updatePortfolioItem($data) {
        // Get current portfolio array
        $currentPortfolio = $this->getUserPortfolio($data['user_id']);
        
        // Find and update the portfolio item with matching ID
        foreach ($currentPortfolio as $key => $item) {
            if ($item->id === $data['id']) {
                $currentPortfolio[$key]->title = $data['title'];
                $currentPortfolio[$key]->description = $data['description'] ?? '';
                $currentPortfolio[$key]->image_url = $data['image_url'] ?? $item->image_url;
                $currentPortfolio[$key]->project_url = $data['project_url'] ?? '';
                break;
            }
        }
        
        // Update the database
        $this->db->query('UPDATE users SET portfolio = :portfolio WHERE id = :user_id');
        $this->db->bind(':portfolio', json_encode($currentPortfolio));
        $this->db->bind(':user_id', $data['user_id']);
        
        return $this->db->execute();
    }
    
    // Delete portfolio item
    public function deletePortfolioItem($id, $userId) {
        // Get current portfolio array
        $currentPortfolio = $this->getUserPortfolio($userId);
        
        // Filter out the portfolio item with matching ID
        $updatedPortfolio = array_filter($currentPortfolio, function($item) use ($id) {
            return $item->id !== $id;
        });
        
        // Reindex array
        $updatedPortfolio = array_values($updatedPortfolio);
        
        // Update the database
        $this->db->query('UPDATE users SET portfolio = :portfolio WHERE id = :user_id');
        $this->db->bind(':portfolio', json_encode($updatedPortfolio));
        $this->db->bind(':user_id', $userId);
        
        return $this->db->execute();
    }
    
    // Get user work history
    public function getUserWorkHistory($userId) {
        $this->db->query('SELECT work_history FROM users WHERE id = :user_id');
        $this->db->bind(':user_id', $userId);
        
        $result = $this->db->single();
        
        if ($result && isset($result->work_history) && !empty($result->work_history)) {
            return json_decode($result->work_history);
        }
        
        return [];
    }
    
    // Add work history item
    public function addWorkHistory($data) {
        // First get current work history array
        $currentWorkHistory = $this->getUserWorkHistory($data['user_id']);
        
        // Create new work history item
        $newWorkHistory = [
            'id' => uniqid(), // Generate a unique ID
            'company' => $data['company'],
            'title' => $data['title'],
            'description' => $data['description'] ?? '',
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'] ?? null,
            'current_job' => isset($data['current_job']) ? true : false,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        // Add to array
        $currentWorkHistory[] = $newWorkHistory;
        
        // Update the user's work history JSON
        $this->db->query('UPDATE users SET work_history = :work_history WHERE id = :user_id');
        $this->db->bind(':work_history', json_encode($currentWorkHistory));
        $this->db->bind(':user_id', $data['user_id']);
        
        return $this->db->execute();
    }
    
    // Update work history item
    public function updateWorkHistory($data) {
        // Get current work history array
        $currentWorkHistory = $this->getUserWorkHistory($data['user_id']);
        
        // Find and update the work history item with matching ID
        foreach ($currentWorkHistory as $key => $job) {
            if ($job->id === $data['id']) {
                $currentWorkHistory[$key]->company = $data['company'];
                $currentWorkHistory[$key]->title = $data['title'];
                $currentWorkHistory[$key]->description = $data['description'] ?? '';
                $currentWorkHistory[$key]->start_date = $data['start_date'];
                $currentWorkHistory[$key]->end_date = $data['end_date'] ?? null;
                $currentWorkHistory[$key]->current_job = isset($data['current_job']) ? true : false;
                break;
            }
        }
        
        // Update the database
        $this->db->query('UPDATE users SET work_history = :work_history WHERE id = :user_id');
        $this->db->bind(':work_history', json_encode($currentWorkHistory));
        $this->db->bind(':user_id', $data['user_id']);
        
        return $this->db->execute();
    }
    
    // Delete work history item
    public function deleteWorkHistory($id, $userId) {
        // Get current work history array
        $currentWorkHistory = $this->getUserWorkHistory($userId);
        
        // Filter out the work history item with matching ID
        $updatedWorkHistory = array_filter($currentWorkHistory, function($job) use ($id) {
            return $job->id !== $id;
        });
        
        // Reindex array
        $updatedWorkHistory = array_values($updatedWorkHistory);
        
        // Update the database
        $this->db->query('UPDATE users SET work_history = :work_history WHERE id = :user_id');
        $this->db->bind(':work_history', json_encode($updatedWorkHistory));
        $this->db->bind(':user_id', $userId);
        
        return $this->db->execute();
    }
    
    // Get user education
    public function getUserEducation($userId) {
        $this->db->query('SELECT education FROM users WHERE id = :user_id');
        $this->db->bind(':user_id', $userId);
        
        $result = $this->db->single();
        
        if ($result && isset($result->education) && !empty($result->education)) {
            return json_decode($result->education);
        }
        
        return [];
    }
    
    // Add education item
    public function addEducation($data) {
        // First get current education array
        $currentEducation = $this->getUserEducation($data['user_id']);
        
        // Create new education item
        $newEducation = [
            'id' => uniqid(), // Generate a unique ID for the education item
            'institution' => $data['institution'],
            'degree' => $data['degree'],
            'field_of_study' => $data['field_of_study'] ?? '',
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'] ?? null,
            'current_education' => isset($data['current_education']) ? true : false,
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        // Add to array
        $currentEducation[] = $newEducation;
        
        // Update the user's education JSON
        $this->db->query('UPDATE users SET education = :education WHERE id = :user_id');
        $this->db->bind(':education', json_encode($currentEducation));
        $this->db->bind(':user_id', $data['user_id']);
        
        return $this->db->execute();
    }
    
    // Update education item
    public function updateEducation($data) {
        // Get current education array
        $currentEducation = $this->getUserEducation($data['user_id']);
        
        // Find and update the education item with matching ID
        foreach ($currentEducation as $key => $education) {
            if ($education->id === $data['id']) {
                $currentEducation[$key]->institution = $data['institution'];
                $currentEducation[$key]->degree = $data['degree'];
                $currentEducation[$key]->field_of_study = $data['field_of_study'] ?? '';
                $currentEducation[$key]->start_date = $data['start_date'];
                $currentEducation[$key]->end_date = $data['end_date'] ?? null;
                $currentEducation[$key]->current_education = isset($data['current_education']) ? true : false;
                break;
            }
        }
        
        // Update the database
        $this->db->query('UPDATE users SET education = :education WHERE id = :user_id');
        $this->db->bind(':education', json_encode($currentEducation));
        $this->db->bind(':user_id', $data['user_id']);
        
        return $this->db->execute();
    }
    
    // Delete education item
    public function deleteEducation($id, $userId) {
        // Get current education array
        $currentEducation = $this->getUserEducation($userId);
        
        // Filter out the education item with matching ID
        $updatedEducation = array_filter($currentEducation, function($education) use ($id) {
            return $education->id !== $id;
        });
        
        // Reindex array
        $updatedEducation = array_values($updatedEducation);
        
        // Update the database
        $this->db->query('UPDATE users SET education = :education WHERE id = :user_id');
        $this->db->bind(':education', json_encode($updatedEducation));
        $this->db->bind(':user_id', $userId);
        
        return $this->db->execute();
    }
    
    // Calculate profile completion percentage
    public function calculateProfileCompletion($userId) {
        // Get user and profile data
        $this->db->query('SELECT u.*, p.* FROM users u 
                          LEFT JOIN user_profiles p ON u.id = p.user_id 
                          WHERE u.id = :id');
        $this->db->bind(':id', $userId);
        $userData = $this->db->single();
        
        // Get portfolio count
        $this->db->query('SELECT COUNT(*) as portfolio_count FROM user_portfolio WHERE user_id = :user_id');
        $this->db->bind(':user_id', $userId);
        $portfolioData = $this->db->single();
        
        // Define profile fields and their weights
        $fields = [
            'name' => 10,
            'bio' => 15,
            'location' => 10,
            'professional_title' => 15,
            'experience_level' => 10,
            'hourly_rate' => 10,
            'hours_per_week' => 5,
            'categories' => 10
        ];
        
        // Portfolio items add extra completion percentage
        $portfolioWeight = 15; // Maximum percentage for portfolio
        $hasPortfolio = ($portfolioData && $portfolioData->portfolio_count > 0);
        $portfolioPercentage = $hasPortfolio ? $portfolioWeight : 0;
        
        // Calculate completion
        $completion = 0;
        foreach ($fields as $field => $weight) {
            if (!empty($userData->$field)) {
                $completion += $weight;
            }
        }
        
        // Add portfolio percentage
        $completion += $portfolioPercentage;
        
        // Ensure not exceeding 100%
        return min(100, $completion);
    }

    // Find user by GitHub ID
    public function findUserByGithubId($githubId) {
        $this->db->query('SELECT * FROM users WHERE github_id = :github_id');
        $this->db->bind(':github_id', $githubId);
        
        $row = $this->db->single();
        
        // Check if row exists
        if($this->db->rowCount() > 0) {
            return $row; // Return user object
        } else {
            return false;
        }
    }

    // Register new user with GitHub data
    public function registerWithGithub($data) {
        // Prepare statement
        $this->db->query('INSERT INTO users (github_id, name, email, password, account_type, country, profile_image, status, created_at, updated_at)
        VALUES (:github_id, :name, :email, :password, :account_type, :country, :profile_image, "active", NOW(), NOW())');
        
        // Bind values
        $this->db->bind(':github_id', $data['github_id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']); // Already hashed in controller
        $this->db->bind(':account_type', $data['account_type']);
        $this->db->bind(':country', $data['country']);
        $this->db->bind(':profile_image', $data['profile_picture']);
        
        // Execute
        if($this->db->execute()) {
            $userId = $this->db->lastInsertId();
            return $this->getUserById($userId); // Return the newly created user object
        } else {
            error_log("GitHub Registration Error: Failed to insert user.");
            return false;
        }
    }

    // Optional: Link GitHub ID to an existing user
    public function linkGithubId($userId, $githubId) {
        $this->db->query('UPDATE users SET github_id = :github_id, updated_at = NOW() WHERE id = :id AND github_id IS NULL');
        $this->db->bind(':github_id', $githubId);
        $this->db->bind(':id', $userId);
        return $this->db->execute();
    }

    // Find user by Google ID
    public function findUserByGoogleId($googleId) {
        $this->db->query('SELECT * FROM users WHERE google_id = :google_id');
        $this->db->bind(':google_id', $googleId);
        
        $row = $this->db->single();
        
        // Check if row exists
        if($this->db->rowCount() > 0) {
            return $row; // Return user object
        } else {
            return false;
        }
    }

    // Register new user with Google data
    public function registerWithGoogle($data) {
        // Prepare statement
        $this->db->query('INSERT INTO users (google_id, name, email, password, account_type, country, profile_image, status, created_at, updated_at) 
                          VALUES (:google_id, :name, :email, :password, :account_type, :country, :profile_image, "active", NOW(), NOW())');
        
        // Bind values
        $this->db->bind(':google_id', $data['google_id'] ?? $data['sub'] ?? null);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']); // Already hashed in controller
        $this->db->bind(':account_type', $data['account_type']);
        $this->db->bind(':country', $data['country'] ?? '');
        
        // Check which key is used for profile image (different parts of code use different keys)
        $profileImage = $data['profile_image'] ?? $data['picture'] ?? $data['profile_picture'] ?? null;
        $this->db->bind(':profile_image', $profileImage);
        
        // Execute
        if($this->db->execute()) {
            $userId = $this->db->lastInsertId();
            return $this->getUserById($userId); // Return the newly created user object
        } else {
            error_log("Google Registration Error: Failed to insert user.");
            return false;
        }
    }

    // Link Google ID to an existing user
    public function linkGoogleId($userId, $googleId) {
        $this->db->query('UPDATE users SET google_id = :google_id, updated_at = NOW() WHERE id = :id AND google_id IS NULL');
        $this->db->bind(':google_id', $googleId);
        $this->db->bind(':id', $userId);
        return $this->db->execute();
    }

    /**
     * Update profile settings (combined update of user and user_profile tables)
     * 
     * @param array $data User and profile data
     * @return bool True if successful, false otherwise
     */
    public function updateProfileSettings($data) {
        // Start transaction to ensure data consistency across tables
        $this->db->beginTransaction();
        
        try {
            // First update the basic user information
            $this->db->query('UPDATE users SET 
                              name = :name,
                              email = :email,
                              updated_at = NOW()
                              WHERE id = :id');
            
            // Bind values
            $this->db->bind(':name', $data['name']);
            $this->db->bind(':email', $data['email']);
            $this->db->bind(':id', $data['id']);
            
            // Execute the first update
            if (!$this->db->execute()) {
                throw new Exception('Failed to update basic user information');
            }
            
            // Prepare profile data
            $profileData = [
                'user_id' => $data['id'],
                'bio' => $data['bio'] ?? null,
                'location' => $data['location'] ?? null,
                'professional_title' => $data['professional_title'] ?? null,
                'experience_level' => $data['experience_level'] ?? 'entry',
                'hourly_rate' => $data['hourly_rate'] ?? 0,
                'hours_per_week' => $data['hours_per_week'] ?? null,
                'profile_visibility' => $data['profile_visibility'] ?? 'public',
                'project_preference' => $data['project_preference'] ?? 'both',
                'categories' => $data['categories'] ?? null
            ];
            
            // Update or create user profile
            if (!$this->updateUserProfile($profileData)) {
                throw new Exception('Failed to update user profile');
            }
            
            // If we got here, everything worked
            $this->db->endTransaction();
            return true;
        } catch (Exception $e) {
            // If anything went wrong, roll back changes
            $this->db->cancelTransaction();
            error_log('Profile update error: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Get available work/project categories
     * 
     * @return array List of project categories
     */
    public function getProjectCategories() {
        return [
            'Web, Mobile & Software Dev' => [
                'Desktop Software Development',
                'E-commerce Development',
                'Game Development',
                'Mobile Development',
                'Product Management',
                'QA & Testing',
                'Scripts & Utilities',
                'Web & Mobile Design',
                'Web Development',
                'Other - Software Development'
            ],
            'IT & Networking' => [
                'Database Administration',
                'ERP / CRM Software',
                'Information Security',
                'Network & System Administration',
                'Other - IT & Networking'
            ],
            'Data Science & Analytics' => [
                'A/B Testing',
                'Data Extraction / ETL',
                'Data Mining & Management',
                'Data Visualization',
                'Machine Learning',
                'Quantitative Analysis',
                'Other - Data Science & Analytics'
            ],
            'Design & Creative' => [
                'Animation',
                'Audio Production',
                'Graphic Design',
                'Illustration',
                'Logo Design & Branding',
                'Photography',
                'Video Production',
                'Other - Design & Creative'
            ],
            'Writing' => [
                'Academic Writing & Research',
                'Article & Blog Writing',
                'Copywriting',
                'Creative Writing',
                'Editing & Proofreading',
                'Technical Writing',
                'Other - Writing'
            ],
            'Translation' => [
                'General Translation',
                'Legal Translation',
                'Medical Translation',
                'Technical Translation',
                'Other - Translation'
            ],
            'Sales & Marketing' => [
                'Display Advertising',
                'Email & Marketing Automation',
                'Lead Generation',
                'Market Research',
                'Marketing Strategy',
                'Search Engine Optimization',
                'Social Media Marketing',
                'Other - Sales & Marketing'
            ],
            'Legal' => [
                'Contract Law',
                'Corporate Law',
                'Intellectual Property',
                'Paralegal Services',
                'Other - Legal'
            ],
            'Admin Support' => [
                'Data Entry',
                'Personal / Virtual Assistant',
                'Project Management',
                'Transcription',
                'Web Research',
                'Other - Admin Support'
            ],
            'Customer Service' => [
                'Customer Service',
                'Technical Support',
                'Other - Customer Service'
            ],
            'Finance & Accounting' => [
                'Accounting',
                'Bookkeeping',
                'Financial Analysis',
                'Tax Preparation',
                'Other - Finance & Accounting'
            ],
            'HR & Training' => [
                'Recruiting',
                'Training & Development',
                'Other - HR & Training'
            ],
            'Engineering & Architecture' => [
                'CAD & AutoCAD',
                'Civil & Structural Engineering',
                'Electrical Engineering',
                'Mechanical Engineering',
                'Other - Engineering & Architecture'
            ]
        ];
    }

    /**
     * Get user settings
     * 
     * @param int $userId The user ID
     * @return object User settings or null
     */
    public function getUserSettings($userId) {
        $this->db->query('SELECT * FROM user_settings WHERE user_id = :user_id');
        $this->db->bind(':user_id', $userId);
        return $this->db->single();
    }
    
    /**
     * Update user settings
     * 
     * @param array $data Settings data
     * @return bool True on success, false on failure
     */
    public function updateUserSettings($data) {
        $this->db->query('UPDATE user_settings SET 
            theme = :theme,
            email_notifications = :email_notifications,
            browser_notifications = :browser_notifications,
            visibility = :visibility,
            project_preference = :project_preference,
            timezone = :timezone,
            language = :language,
            currency = :currency,
            updated_at = NOW()
            WHERE user_id = :user_id');
            
        $this->db->bind(':theme', $data['theme'] ?? 'light');
        $this->db->bind(':email_notifications', isset($data['email_notifications']) ? 1 : 0);
        $this->db->bind(':browser_notifications', isset($data['browser_notifications']) ? 1 : 0);
        $this->db->bind(':visibility', $data['visibility'] ?? 'public');
        $this->db->bind(':project_preference', $data['project_preference'] ?? 'both');
        $this->db->bind(':timezone', $data['timezone'] ?? 'UTC');
        $this->db->bind(':language', $data['language'] ?? 'en');
        $this->db->bind(':currency', $data['currency'] ?? 'USD');
        $this->db->bind(':user_id', $data['user_id']);
        
        return $this->db->execute();
    }

    /**
     * Update user's dashboard widgets
     * 
     * @param int $userId User ID
     * @param array $widgets Widget configuration
     * @return bool True on success, false on failure
     */
    public function updateDashboardWidgets($userId, $widgets) {
        $this->db->query('UPDATE user_settings SET 
            dashboard_widgets = :widgets,
            updated_at = NOW()
            WHERE user_id = :user_id');
            
        $this->db->bind(':widgets', json_encode($widgets));
        $this->db->bind(':user_id', $userId);
        
        return $this->db->execute();
    }
    
    /**
     * Toggle two-factor authentication
     * 
     * @param int $userId User ID
     * @param bool $enabled Whether to enable or disable
     * @return bool True on success, false on failure
     */
    public function toggleTwoFactorAuth($userId, $enabled) {
        $this->db->query('UPDATE user_settings SET 
            two_factor_enabled = :enabled,
            updated_at = NOW()
            WHERE user_id = :user_id');
            
        $this->db->bind(':enabled', $enabled ? 1 : 0);
        $this->db->bind(':user_id', $userId);
        
        return $this->db->execute();
    }
    
    /**
     * Create a new user session (for remember me functionality)
     * 
     * @param int $userId User ID
     * @param string $token Session token
     * @param string $ip IP address
     * @param string $userAgent User agent
     * @param int $days Days until expiration
     * @return bool True on success, false on failure
     */
    public function createUserSession($userId, $token, $ip, $userAgent, $days = 30) {
        $this->db->query('INSERT INTO user_sessions 
            (user_id, token, ip_address, user_agent, expires_at) 
            VALUES (:user_id, :token, :ip, :user_agent, DATE_ADD(NOW(), INTERVAL :days DAY))');
            
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':token', $token);
        $this->db->bind(':ip', $ip);
        $this->db->bind(':user_agent', $userAgent);
        $this->db->bind(':days', $days);
        
        return $this->db->execute();
    }
    
    /**
     * Get user session by token
     * 
     * @param string $token Session token
     * @return object|bool Session data or false
     */
    public function getUserSessionByToken($token) {
        $this->db->query('SELECT * FROM user_sessions 
            WHERE token = :token AND is_active = 1 AND expires_at > NOW()');
            
        $this->db->bind(':token', $token);
        return $this->db->single();
    }
    
    /**
     * Update user's last active timestamp
     * 
     * @param int $userId User ID
     * @return bool True on success, false on failure
     */
    public function updateLastActive($userId) {
        $this->db->query('UPDATE users SET last_active = NOW() WHERE id = :id');
        $this->db->bind(':id', $userId);
        return $this->db->execute();
    }

    /**
     * Update a single field in the user's profile
     * 
     * @param int $userId The user ID
     * @param string $field The field to update
     * @param mixed $value The new value for the field
     * @return bool True on success, false on failure
     */
    public function updateSingleField($userId, $field, $value) {
        // Validate field to prevent SQL injection
        $allowedFields = [
            'name', 'email', 'bio', 'location', 'hourly_rate', 
            'professional_title', 'profile_visibility', 'visibility',
            'experience_level', 'project_preference', 'language', 
            'timezone', 'currency', 'website', 'linkedin', 
            'github', 'twitter', 'skills', 'categories'
        ];
        
        if (!in_array($field, $allowedFields)) {
            error_log('Attempted to update disallowed field: ' . $field);
            return false;
        }
        
        // Handle special case for profile_visibility/visibility field (database uses one, code uses the other)
        if ($field === 'profile_visibility') {
            $field = 'visibility';
        }
        
        // Create SQL query with parameterized field
        $sql = "UPDATE users SET $field = :value WHERE id = :user_id";
        $this->db->query($sql);
        
        // Bind values
        $this->db->bind(':value', $value);
        $this->db->bind(':user_id', $userId);
        
        // Execute and return result
        return $this->db->execute();
    }

    /**
     * Search for freelancers based on a query string
     * 
     * @param string $query Search query
     * @param array $filters Additional filters
     * @return array Freelancer results
     */
    public function searchFreelancers($query, $filters = []) {
        // Base SQL query
        $sql = "SELECT 
                u.id, 
                u.name, 
                u.email, 
                u.account_type,
                u.profile_image,
                u.bio,
                u.location,
                u.country,
                u.professional_title,
                u.hourly_rate,
                u.experience_level,
                u.skills,
                u.education,
                u.work_history,
                u.visibility
            FROM users u
            WHERE u.account_type = 'freelancer' 
                AND u.status = 'active'
                AND (u.visibility = 'public' OR u.visibility = 'connections')";
        
        // Add search conditions
        if (!empty($query)) {
            $sql .= " AND (
                u.name LIKE :query_like
                OR u.professional_title LIKE :query_like
                OR u.bio LIKE :query_like
                OR JSON_CONTAINS(u.skills, JSON_ARRAY(:query_exact), '$')
            )";
        }
        
        // Add experience level filter
        if (isset($filters['experience_level']) && !empty($filters['experience_level'])) {
            $sql .= " AND u.experience_level = :experience_level";
        }
        
        // Add country filter
        if (isset($filters['country']) && !empty($filters['country'])) {
            $sql .= " AND u.country = :country";
        }
        
        // Add hourly rate range filter
        if (isset($filters['min_rate']) && is_numeric($filters['min_rate'])) {
            $sql .= " AND u.hourly_rate >= :min_rate";
        }
        
        if (isset($filters['max_rate']) && is_numeric($filters['max_rate'])) {
            $sql .= " AND u.hourly_rate <= :max_rate";
        }
        
        // Order by relevance based on skill matches
        if (!empty($query)) {
            $sql .= " ORDER BY (
                CASE 
                    WHEN JSON_CONTAINS(u.skills, JSON_ARRAY(:query_order), '$') THEN 3
                    WHEN u.professional_title LIKE :query_title THEN 2
                    ELSE 1
                END
            ) DESC, u.hourly_rate DESC";
        } else {
            // Default ordering by experience level and hourly rate
            $sql .= " ORDER BY 
                CASE u.experience_level
                    WHEN 'expert' THEN 1
                    WHEN 'intermediate' THEN 2
                    WHEN 'entry' THEN 3
                    ELSE 4
                END,
                u.hourly_rate DESC";
        }
        
        // Add limit if specified
        if (isset($filters['limit']) && is_numeric($filters['limit'])) {
            $sql .= " LIMIT :limit";
            if (isset($filters['offset']) && is_numeric($filters['offset'])) {
                $sql .= " OFFSET :offset";
            }
        }
        
        $this->db->query($sql);
        
        // Bind search parameters
        if (!empty($query)) {
            $this->db->bind(':query_like', '%' . $query . '%');
            $this->db->bind(':query_exact', $query);
            $this->db->bind(':query_order', $query);
            $this->db->bind(':query_title', '%' . $query . '%');
        }
        
        // Bind filter parameters
        if (isset($filters['experience_level']) && !empty($filters['experience_level'])) {
            $this->db->bind(':experience_level', $filters['experience_level']);
        }
        
        if (isset($filters['country']) && !empty($filters['country'])) {
            $this->db->bind(':country', $filters['country']);
        }
        
        if (isset($filters['min_rate']) && is_numeric($filters['min_rate'])) {
            $this->db->bind(':min_rate', $filters['min_rate']);
        }
        
        if (isset($filters['max_rate']) && is_numeric($filters['max_rate'])) {
            $this->db->bind(':max_rate', $filters['max_rate']);
        }
        
        if (isset($filters['limit']) && is_numeric($filters['limit'])) {
            $this->db->bind(':limit', $filters['limit']);
            if (isset($filters['offset']) && is_numeric($filters['offset'])) {
                $this->db->bind(':offset', $filters['offset']);
            }
        }
        
        return $this->db->resultSet();
    }


}