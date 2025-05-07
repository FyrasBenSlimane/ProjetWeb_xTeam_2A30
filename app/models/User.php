<?php
class User {
    private $db;
    
    public function __construct() {
        $this->db = new Database;
    }
    
    // Register new user
    public function register($data) {
        // Prepare statement
        $this->db->query('INSERT INTO users (name, email, password, account_type) VALUES (:name, :email, :password, :account_type)');
        
        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':account_type', $data['account_type']);
        
        // Execute
        if($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }
    
    // Login user
    public function login($email, $password) {
        $this->db->query('SELECT * FROM users WHERE email = :email AND status = "active"');
        $this->db->bind(':email', $email);
        
        $row = $this->db->single();
        
        if($row) {
            $hashed_password = $row->password;
            if(password_verify($password, $hashed_password)) {
                return $row;
            }
        }
        
        return false;
    }
    
    // Find user by email
    public function findUserByEmail($email) {
        $this->db->query('SELECT * FROM users WHERE email = :email');
        $this->db->bind(':email', $email);
        
        $row = $this->db->single();
        
        // Check if row exists
        if($this->db->rowCount() > 0) {
            return true;
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
    
    // Update user profile
    public function updateProfile($data) {
        // Prepare statement
        $this->db->query('UPDATE users SET name = :name, email = :email WHERE id = :id');
        
        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':id', $data['id']);
        
        // Execute
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Update user password
    public function updatePassword($id, $password) {
        // Prepare statement
        $this->db->query('UPDATE users SET password = :password WHERE id = :id');
        
        // Bind values
        $this->db->bind(':password', $password);
        $this->db->bind(':id', $id);
        
        // Execute
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Force user to change password on next login
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
        // Check if we're updating the password
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
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':account_type', $data['type']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':id', $data['id']);
        
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

    // Get user profile data - enhanced with detailed profile information
    public function getUserProfile($userId) {
        $this->db->query('SELECT u.*, 
                          p.bio as profile_bio, 
                          p.location, 
                          p.professional_title, 
                          p.experience_level, 
                          p.hourly_rate, 
                          p.hours_per_week,
                          p.profile_visibility, 
                          p.project_preference, 
                          p.categories, 
                          p.languages, 
                          p.skills as profile_skills,
                          p.education, 
                          p.certifications
                          FROM users u
                          LEFT JOIN user_profiles p ON u.id = p.user_id 
                          WHERE u.id = :id');
        $this->db->bind(':id', $userId);
        return $this->db->single();
    }
    
    // Check if a user profile exists
    public function profileExists($userId) {
        $this->db->query('SELECT id FROM user_profiles WHERE user_id = :user_id');
        $this->db->bind(':user_id', $userId);
        $this->db->execute();
        return $this->db->rowCount() > 0;
    }

    // Create or update user profile
    public function updateUserProfile($data) {
        // Check if profile exists
        if ($this->profileExists($data['user_id'])) {
            // Update existing profile
            $this->db->query('UPDATE user_profiles SET 
                              bio = :bio,
                              location = :location,
                              professional_title = :professional_title,
                              experience_level = :experience_level,
                              hourly_rate = :hourly_rate,
                              hours_per_week = :hours_per_week,
                              profile_visibility = :profile_visibility,
                              project_preference = :project_preference,
                              categories = :categories,
                              languages = :languages,
                              skills = :skills,
                              education = :education,
                              certifications = :certifications,
                              updated_at = NOW()
                              WHERE user_id = :user_id');
        } else {
            // Create new profile
            $this->db->query('INSERT INTO user_profiles (
                              user_id, bio, location, professional_title, experience_level,
                              hourly_rate, hours_per_week, profile_visibility, project_preference,
                              categories, languages, skills, education, certifications, created_at, updated_at
                              ) VALUES (
                              :user_id, :bio, :location, :professional_title, :experience_level,
                              :hourly_rate, :hours_per_week, :profile_visibility, :project_preference,
                              :categories, :languages, :skills, :education, :certifications, NOW(), NOW()
                              )');
        }
        
        // Bind values
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':bio', $data['bio'] ?? null);
        $this->db->bind(':location', $data['location'] ?? null);
        $this->db->bind(':professional_title', $data['professional_title'] ?? null);
        $this->db->bind(':experience_level', $data['experience_level'] ?? 'entry');
        $this->db->bind(':hourly_rate', $data['hourly_rate'] ?? 0);
        $this->db->bind(':hours_per_week', $data['hours_per_week'] ?? null);
        $this->db->bind(':profile_visibility', $data['profile_visibility'] ?? 'public');
        $this->db->bind(':project_preference', $data['project_preference'] ?? 'both');
        $this->db->bind(':categories', $data['categories'] ?? null);
        $this->db->bind(':languages', $data['languages'] ?? null);
        $this->db->bind(':skills', $data['skills'] ?? null);
        $this->db->bind(':education', $data['education'] ?? null);
        $this->db->bind(':certifications', $data['certifications'] ?? null);
        
        // Execute
        return $this->db->execute();
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
        $this->db->query('SELECT * FROM user_portfolio WHERE user_id = :user_id ORDER BY created_at DESC');
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }
    
    // Add portfolio item
    public function addPortfolioItem($data) {
        $this->db->query('INSERT INTO user_portfolio 
                        (user_id, title, description, image_url, project_url, created_at) 
                        VALUES 
                        (:user_id, :title, :description, :image_url, :project_url, NOW())');
        
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':description', $data['description'] ?? null);
        $this->db->bind(':image_url', $data['image_url'] ?? null);
        $this->db->bind(':project_url', $data['project_url'] ?? null);
        
        return $this->db->execute() ? $this->db->lastInsertId() : false;
    }
    
    // Update portfolio item
    public function updatePortfolioItem($data) {
        $this->db->query('UPDATE user_portfolio SET 
                          title = :title,
                          description = :description,
                          image_url = :image_url,
                          project_url = :project_url
                          WHERE id = :id AND user_id = :user_id');
        
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':description', $data['description'] ?? null);
        $this->db->bind(':image_url', $data['image_url'] ?? null);
        $this->db->bind(':project_url', $data['project_url'] ?? null);
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':user_id', $data['user_id']);
        
        return $this->db->execute();
    }
    
    // Delete portfolio item
    public function deletePortfolioItem($id, $userId) {
        $this->db->query('DELETE FROM user_portfolio WHERE id = :id AND user_id = :user_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }
    
    // Get user work history
    public function getUserWorkHistory($userId) {
        $this->db->query('SELECT * FROM user_work_history WHERE user_id = :user_id ORDER BY
                         CASE WHEN current_job = 1 THEN 0 ELSE 1 END,
                         end_date DESC, start_date DESC');
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }
    
    // Add work history item
    public function addWorkHistory($data) {
        // If this is current job, set any other current jobs to false
        if (!empty($data['current_job'])) {
            $this->db->query('UPDATE user_work_history SET current_job = 0 WHERE user_id = :user_id AND current_job = 1');
            $this->db->bind(':user_id', $data['user_id']);
            $this->db->execute();
        }
        
        $this->db->query('INSERT INTO user_work_history 
                         (user_id, company, position, description, start_date, end_date, current_job, created_at) 
                         VALUES (:user_id, :company, :position, :description, :start_date, :end_date, :current_job, NOW())');
        
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':company', $data['company']);
        $this->db->bind(':position', $data['position']);
        $this->db->bind(':description', $data['description'] ?? null);
        $this->db->bind(':start_date', $data['start_date'] ?? null);
        $this->db->bind(':end_date', $data['end_date'] ?? null);
        $this->db->bind(':current_job', $data['current_job'] ?? 0);
        
        return $this->db->execute() ? $this->db->lastInsertId() : false;
    }
    
    // Update work history item
    public function updateWorkHistory($data) {
        // If this is current job, set any other current jobs to false
        if (!empty($data['current_job'])) {
            $this->db->query('UPDATE user_work_history SET current_job = 0 
                             WHERE user_id = :user_id AND current_job = 1 AND id != :id');
            $this->db->bind(':user_id', $data['user_id']);
            $this->db->bind(':id', $data['id']);
            $this->db->execute();
        }
        
        $this->db->query('UPDATE user_work_history SET 
                         company = :company,
                         position = :position,
                         description = :description,
                         start_date = :start_date,
                         end_date = :end_date,
                         current_job = :current_job
                         WHERE id = :id AND user_id = :user_id');
        
        $this->db->bind(':company', $data['company']);
        $this->db->bind(':position', $data['position']);
        $this->db->bind(':description', $data['description'] ?? null);
        $this->db->bind(':start_date', $data['start_date'] ?? null);
        $this->db->bind(':end_date', $data['end_date'] ?? null);
        $this->db->bind(':current_job', $data['current_job'] ?? 0);
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':user_id', $data['user_id']);
        
        return $this->db->execute();
    }
    
    // Delete work history item
    public function deleteWorkHistory($id, $userId) {
        $this->db->query('DELETE FROM user_work_history WHERE id = :id AND user_id = :user_id');
        $this->db->bind(':id', $id);
        $this->db->bind(':user_id', $userId);
        return $this->db->execute();
    }
    
    // Get user education
    public function getUserEducation($userId) {
        $this->db->query('SELECT * FROM user_education WHERE user_id = :user_id ORDER BY
                         CASE WHEN current_education = 1 THEN 0 ELSE 1 END,
                         end_date DESC, start_date DESC');
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }
    
    // Add education item
    public function addEducation($data) {
        // If this is current education, set any other current educations to false
        if (!empty($data['current_education'])) {
            $this->db->query('UPDATE user_education SET current_education = 0 WHERE user_id = :user_id AND current_education = 1');
            $this->db->bind(':user_id', $data['user_id']);
            $this->db->execute();
        }
        
        $this->db->query('INSERT INTO user_education 
                         (user_id, institution, degree, field_of_study, start_date, end_date, current_education, created_at) 
                         VALUES (:user_id, :institution, :degree, :field_of_study, :start_date, :end_date, :current_education, NOW())');
        
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':institution', $data['institution']);
        $this->db->bind(':degree', $data['degree'] ?? null);
        $this->db->bind(':field_of_study', $data['field_of_study'] ?? null);
        $this->db->bind(':start_date', $data['start_date'] ?? null);
        $this->db->bind(':end_date', $data['end_date'] ?? null);
        $this->db->bind(':current_education', $data['current_education'] ?? 0);
        
        return $this->db->execute() ? $this->db->lastInsertId() : false;
    }
    
    // Update education item
    public function updateEducation($data) {
        // If this is current education, set any other current educations to false
        if (!empty($data['current_education'])) {
            $this->db->query('UPDATE user_education SET current_education = 0 
                             WHERE user_id = :user_id AND current_education = 1 AND id != :id');
            $this->db->bind(':user_id', $data['user_id']);
            $this->db->bind(':id', $data['id']);
            $this->db->execute();
        }
        
        $this->db->query('UPDATE user_education SET 
                         institution = :institution,
                         degree = :degree,
                         field_of_study = :field_of_study,
                         start_date = :start_date,
                         end_date = :end_date,
                         current_education = :current_education
                         WHERE id = :id AND user_id = :user_id');
        
        $this->db->bind(':institution', $data['institution']);
        $this->db->bind(':degree', $data['degree'] ?? null);
        $this->db->bind(':field_of_study', $data['field_of_study'] ?? null);
        $this->db->bind(':start_date', $data['start_date'] ?? null);
        $this->db->bind(':end_date', $data['end_date'] ?? null);
        $this->db->bind(':current_education', $data['current_education'] ?? 0);
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':user_id', $data['user_id']);
        
        return $this->db->execute();
    }
    
    // Delete education item
    public function deleteEducation($id, $userId) {
        $this->db->query('DELETE FROM user_education WHERE id = :id AND user_id = :user_id');
        $this->db->bind(':id', $id);
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
        $this->db->query('INSERT INTO users (github_id, name, email, password, account_type, country, profile_picture, status, created_at, updated_at) 
                          VALUES (:github_id, :name, :email, :password, :account_type, :country, :profile_picture, "active", NOW(), NOW())');
        
        // Bind values
        $this->db->bind(':github_id', $data['github_id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']); // Already hashed in controller
        $this->db->bind(':account_type', $data['account_type']);
        $this->db->bind(':country', $data['country']);
        $this->db->bind(':profile_picture', $data['profile_picture']);
        
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
}