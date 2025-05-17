<?php
/**
 * ProjectsModel
 * Handles all data operations related to collaborative projects
 */
class ProjectsModel {
    private $db;

    public function __construct() {
        $this->db = new Database;
        
        // Create necessary tables if they don't exist
        $this->createProjectTablesIfNeeded();
    }
    
    /**
     * Create projects tables if they don't exist
     */
    private function createProjectTablesIfNeeded() {
        try {
            // Use INFORMATION_SCHEMA to check if projects table exists
            $this->db->query("SELECT COUNT(*) as count FROM INFORMATION_SCHEMA.TABLES 
                            WHERE TABLE_SCHEMA = :dbname 
                            AND TABLE_NAME = 'projects'");
            $this->db->bind(':dbname', DB_NAME);
            $result = $this->db->single();
            $projectsTableExists = ($result && $result->count > 0);

            $insertSamples = false;

            if (!$projectsTableExists) {
                // Create projects table
                $this->db->query("CREATE TABLE projects (
                    id INT AUTO_INCREMENT PRIMARY KEY,
                    title VARCHAR(255) NOT NULL,
                    description TEXT NOT NULL,
                    status ENUM('draft', 'active', 'completed', 'canceled') DEFAULT 'draft',
                    category VARCHAR(50) NOT NULL,
                    start_date DATETIME,
                    end_date DATETIME,
                    max_participants INT,
                    skills_required TEXT,
                    location VARCHAR(255),
                    is_remote BOOLEAN DEFAULT 1,
                    image VARCHAR(255),
                    created_by INT NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                    FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
                )");
                $this->db->execute();
                $insertSamples = true; // Table just created, so insert samples

                // Check if candidatures table exists (only create if projects table was just created)
                $this->db->query("SELECT COUNT(*) as count FROM INFORMATION_SCHEMA.TABLES 
                                WHERE TABLE_SCHEMA = :dbname 
                                AND TABLE_NAME = 'project_candidatures'");
                $this->db->bind(':dbname', DB_NAME);
                $resultCandidatures = $this->db->single();
                $candidaturesTableExists = ($resultCandidatures && $resultCandidatures->count > 0);

                if (!$candidaturesTableExists) {
                    // Create project_candidatures table
                    $this->db->query("CREATE TABLE project_candidatures (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        project_id INT NOT NULL,
                        user_id INT NOT NULL,
                        status ENUM('pending', 'approved', 'rejected', 'left') DEFAULT 'pending',
                        message TEXT,
                        skills TEXT,
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                        FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE,
                        FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
                    )");
                    $this->db->execute();
                }
            } else {
                // Projects table exists, check if it's empty
                $this->db->query("SELECT COUNT(*) as count FROM projects");
                $projectCountResult = $this->db->single();
                if ($projectCountResult && $projectCountResult->count == 0) {
                    $insertSamples = true; // Table exists but is empty, insert samples
                }
            }

            if ($insertSamples) {
                $this->insertSampleProjects();
            }
        } catch (Exception $e) {
            // Log the error but don't stop execution
            error_log("Error checking/creating projects tables: " . $e->getMessage());
        }
    }
    
    /**
     * Insert sample projects
     */
    private function insertSampleProjects() {
        // Make sure we have at least one admin user
        $this->db->query("SELECT id FROM users WHERE account_type = 'admin' LIMIT 1");
        $admin = $this->db->single();
        
        if (!$admin) {
            error_log("ProjectsModel::insertSampleProjects: No admin user found. Skipping sample project insertion.");
            return;
        }
        
        // Sample projects data
        $projects = [
            [
                'title' => 'Web Development Portfolio Site',
                'description' => 'Collaborate on building a professional portfolio website for developers. This project will use modern technologies like React, Next.js and TailwindCSS to create a responsive and visually appealing portfolio that team members can customize for their own use.',
                'category' => 'web-development',
                'start_date' => date('Y-m-d H:i:s'),
                'end_date' => date('Y-m-d H:i:s', strtotime('+30 days')),
                'max_participants' => 4,
                'skills_required' => 'React, Next.js, TailwindCSS, JavaScript',
                'location' => 'Remote',
                'is_remote' => 1,
                'image' => 'https://images.unsplash.com/photo-1517180102446-f3ece451e9d8?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80',
                'status' => 'active',
                'created_by' => $admin->id
            ],
            [
                'title' => 'Mobile App for Local Businesses',
                'description' => 'Create a mobile application that helps connect users with local small businesses. The app will feature business listings, reviews, and special deals to promote shopping locally.',
                'category' => 'mobile-app',
                'start_date' => date('Y-m-d H:i:s', strtotime('+5 days')),
                'end_date' => date('Y-m-d H:i:s', strtotime('+60 days')),
                'max_participants' => 5,
                'skills_required' => 'Flutter, Firebase, UI/UX Design, API Development',
                'location' => 'Remote',
                'is_remote' => 1,
                'image' => 'https://images.unsplash.com/photo-1551650975-87deedd944c3?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80',
                'status' => 'active',
                'created_by' => $admin->id
            ],
            [
                'title' => 'UI/UX Redesign for Education Platform',
                'description' => 'Redesign the user interface and experience for an educational platform that serves K-12 students. We\'ll focus on creating an engaging, accessible, and easy-to-use interface that improves learning outcomes.',
                'category' => 'design',
                'start_date' => date('Y-m-d H:i:s', strtotime('+2 days')),
                'end_date' => date('Y-m-d H:i:s', strtotime('+45 days')),
                'max_participants' => 3,
                'skills_required' => 'UI Design, UX Research, Figma, Prototyping',
                'location' => 'Remote',
                'is_remote' => 1,
                'image' => 'https://images.unsplash.com/photo-1581291518633-83b4ebd1d83e?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80',
                'status' => 'active',
                'created_by' => $admin->id
            ],
            [
                'title' => 'Social Media Marketing Campaign',
                'description' => 'Develop and execute a social media marketing campaign for a new eco-friendly product line. This will include content creation, scheduling, analytics, and optimization across multiple platforms.',
                'category' => 'marketing',
                'start_date' => date('Y-m-d H:i:s', strtotime('+3 days')),
                'end_date' => date('Y-m-d H:i:s', strtotime('+21 days')),
                'max_participants' => 4,
                'skills_required' => 'Social Media Marketing, Content Creation, Analytics, Copywriting',
                'location' => 'Remote',
                'is_remote' => 1,
                'image' => 'https://images.unsplash.com/photo-1611926653458-09294b3142bf?ixlib=rb-1.2.1&auto=format&fit=crop&w=1000&q=80',
                'status' => 'active',
                'created_by' => $admin->id
            ]
        ];
        
        // Insert sample projects
        foreach ($projects as $project) {
            $this->db->query("INSERT INTO projects (
                title, description, category, start_date, end_date, max_participants,
                skills_required, location, is_remote, image, status, created_by
            ) VALUES (
                :title, :description, :category, :start_date, :end_date, :max_participants,
                :skills_required, :location, :is_remote, :image, :status, :created_by
            )");
            
            $this->db->bind(':title', $project['title']);
            $this->db->bind(':description', $project['description']);
            $this->db->bind(':category', $project['category']);
            $this->db->bind(':start_date', $project['start_date']);
            $this->db->bind(':end_date', $project['end_date']);
            $this->db->bind(':max_participants', $project['max_participants']);
            $this->db->bind(':skills_required', $project['skills_required']);
            $this->db->bind(':location', $project['location']);
            $this->db->bind(':is_remote', $project['is_remote']);
            $this->db->bind(':image', $project['image']);
            $this->db->bind(':status', $project['status']);
            $this->db->bind(':created_by', $project['created_by']);
            
            $this->db->execute();
        }
    }
    
    /**
     * Get all active projects
     * 
     * @return array Projects
     */
    public function getActiveProjects() {
        $this->db->query("SELECT p.*, u.name as creator_name 
                        FROM projects p
                        LEFT JOIN users u ON p.created_by = u.id
                        WHERE p.status = 'active'
                        ORDER BY p.start_date");
        
        return $this->db->resultSet();
    }
    
    /**
     * Get all projects with optional status filter
     * 
     * @param string $status Status filter
     * @return array Projects
     */
    public function getAllProjects($status = 'all') {
        $statusClause = ($status !== 'all') ? 'WHERE p.status = :status' : '';
        
        $this->db->query("SELECT p.*, u.name as creator_name 
                        FROM projects p
                        LEFT JOIN users u ON p.created_by = u.id
                        $statusClause
                        ORDER BY p.start_date");
        
        if ($status !== 'all') {
            $this->db->bind(':status', $status);
        }
        
        return $this->db->resultSet();
    }
    
    /**
     * Get project by ID
     * 
     * @param int $id Project ID
     * @return object Project
     */
    public function getProjectById($id) {
        $this->db->query("SELECT p.*, u.name as creator_name 
                        FROM projects p
                        LEFT JOIN users u ON p.created_by = u.id
                        WHERE p.id = :id");
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }
    
    /**
     * Add a new project
     * 
     * @param array $data Project data
     * @return bool Success/failure
     */
    public function addProject($data) {
        $this->db->query("INSERT INTO projects (
                        title, description, category, start_date, end_date,
                        location, is_remote, max_participants, skills_required,
                        image, status, created_by
                        ) VALUES (
                        :title, :description, :category, :start_date, :end_date,
                        :location, :is_remote, :max_participants, :skills_required,
                        :image, :status, :created_by
                        )");
        
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
        return $this->db->execute();
    }
    
    /**
     * Update a project
     * 
     * @param array $data Project data
     * @return bool Success/failure
     */
    public function updateProject($data) {
        $this->db->query("UPDATE projects SET 
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
                        WHERE id = :id");
        
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
        return $this->db->execute();
    }
    
    /**
     * Delete a project
     * 
     * @param int $id Project ID
     * @return bool Success/failure
     */
    public function deleteProject($id) {
        // Delete project (candidatures will be deleted automatically via foreign key constraint)
        $this->db->query("DELETE FROM projects WHERE id = :id");
        $this->db->bind(':id', $id);
        
        return $this->db->execute();
    }
    
    /**
     * Add a candidature to a project
     * 
     * @param array $data Candidature data
     * @return bool Success/failure
     */
    public function addCandidature($data) {
        $this->db->query("INSERT INTO project_candidatures (
                        project_id, user_id, message, skills, status
                        ) VALUES (
                        :project_id, :user_id, :message, :skills, :status
                        )");
        
        // Bind values
        $this->db->bind(':project_id', $data['project_id']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':message', $data['message']);
        $this->db->bind(':skills', $data['skills']);
        $this->db->bind(':status', $data['status']);
        
        // Execute
        return $this->db->execute();
    }
    
    /**
     * Check if a user has already applied to a project
     * 
     * @param int $projectId Project ID
     * @param int $userId User ID
     * @return bool Has applied or not
     */
    public function hasUserCandidature($projectId, $userId) {
        $this->db->query("SELECT id FROM project_candidatures 
                        WHERE project_id = :project_id AND user_id = :user_id");
        $this->db->bind(':project_id', $projectId);
        $this->db->bind(':user_id', $userId);
        
        $this->db->execute();
        return $this->db->rowCount() > 0;
    }
    
    /**
     * Get all candidatures for a specific project
     * 
     * @param int $projectId Project ID
     * @return array Candidatures
     */
    public function getProjectCandidatures($projectId) {
        $this->db->query("SELECT c.*, u.name as user_name, u.email as user_email 
                        FROM project_candidatures c
                        LEFT JOIN users u ON c.user_id = u.id
                        WHERE c.project_id = :project_id
                        ORDER BY c.created_at DESC");
        $this->db->bind(':project_id', $projectId);
        
        return $this->db->resultSet();
    }
    
    /**
     * Get candidatures for a specific user
     * 
     * @param int $userId User ID
     * @return array Candidatures
     */
    public function getUserCandidatures($userId) {
        $this->db->query("SELECT c.*, p.title as project_title, p.category as project_category 
                        FROM project_candidatures c
                        LEFT JOIN projects p ON c.project_id = p.id
                        WHERE c.user_id = :user_id
                        ORDER BY c.created_at DESC");
        $this->db->bind(':user_id', $userId);
        
        return $this->db->resultSet();
    }
    
    /**
     * Update candidature status
     * 
     * @param int $id Candidature ID
     * @param string $status New status
     * @return bool Success/failure
     */
    public function updateCandidatureStatus($id, $status) {
        $this->db->query("UPDATE project_candidatures SET status = :status WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->bind(':status', $status);
        
        return $this->db->execute();
    }
    
    /**
     * Count approved participants for a project
     * 
     * @param int $projectId Project ID
     * @return int Number of approved participants
     */
    public function countProjectParticipants($projectId) {
        $this->db->query("SELECT COUNT(*) as count FROM project_candidatures 
                        WHERE project_id = :project_id AND status = 'approved'");
        $this->db->bind(':project_id', $projectId);
        
        $result = $this->db->single();
        return $result->count;
    }
    
    /**
     * Get projects by category
     * 
     * @param string $category Category
     * @return array Projects
     */
    public function getProjectsByCategory($category) {
        $this->db->query("SELECT p.*, u.name as creator_name 
                        FROM projects p
                        LEFT JOIN users u ON p.created_by = u.id
                        WHERE p.category = :category AND p.status = 'active'
                        ORDER BY p.start_date");
        $this->db->bind(':category', $category);
        
        return $this->db->resultSet();
    }
    
    /**
     * Get featured projects
     * 
     * @param int $limit Number of projects to return
     * @return array Projects
     */
    public function getFeaturedProjects($limit = 3) {
        $this->db->query("SELECT p.*, u.name as creator_name,
                        (SELECT COUNT(*) FROM project_candidatures
                         WHERE project_id = p.id AND status = 'approved') as participants_count
                        FROM projects p
                        LEFT JOIN users u ON p.created_by = u.id
                        WHERE p.status = 'active'
                        ORDER BY participants_count DESC, p.created_at DESC
                        LIMIT :limit");
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }
    
    /**
     * Get recent projects
     * 
     * @param int $limit Number of projects to return
     * @return array Projects
     */
    public function getRecentProjects($limit = 4) {
        $this->db->query("SELECT p.*, u.name as creator_name 
                        FROM projects p
                        LEFT JOIN users u ON p.created_by = u.id
                        WHERE p.status = 'active'
                        ORDER BY p.created_at DESC
                        LIMIT :limit");
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        
        return $this->db->resultSet();
    }
} 