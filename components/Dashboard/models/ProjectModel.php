<?php
/**
 * Project Model
 * Handles all project data operations
 */
class ProjectModel {
    public $db;

    public function __construct($db = null) {
        if ($db) {
            $this->db = $db;
        } else {
            // Get database connection
            require_once __DIR__ . '/../../../config/database.php';
            $this->db = $GLOBALS['pdo'] ?? getDBConnection();
        }
    }

    /**
     * Get all projects for a user
     * 
     * @param string $userEmail User email
     * @return array Array of projects
     */
    public function getUserProjects($userEmail = null) {
        // Si aucun email n'est fourni, récupérer tous les projets
        if ($userEmail === null) {
            $sql = "SELECT * FROM projects ORDER BY created_at DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute();
        } else {
            $sql = "SELECT * FROM projects WHERE user_email = ? ORDER BY created_at DESC";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$userEmail]);
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get a single project by ID
     * 
     * @param int $projectId Project ID
     * @param string $userEmail User email (for permission checking, optional)
     * @return array|bool Project data or false if not found
     */
    public function getProjectById($projectId, $userEmail = null) {
        if ($userEmail !== null) {
            // If user email is provided, check permission
            $sql = "SELECT * FROM projects WHERE id = ? AND user_email = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$projectId, $userEmail]);
        } else {
            // Without user email, just get the project details
            $sql = "SELECT * FROM projects WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$projectId]);
        }
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Create a new project
     * 
     * @param string $userEmail User email
     * @param string $title Project title
     * @param string $description Project description
     * @param string $clientName Client name
     * @param float $budget Project budget
     * @param string $priority Project priority
     * @param string $startDate Project start date
     * @param string $endDate Project end date
     * @return int|bool New project ID or false on failure
     */
    public function createProject($userEmail, $title, $description, $clientName, $budget, $priority = 'medium', $startDate = null, $endDate = null) {
        $sql = "INSERT INTO projects (title, description, user_email, client_name, budget, priority, start_date, end_date) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([$title, $description, $userEmail, $clientName, $budget, $priority, $startDate, $endDate]);
        
        if ($result) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }

    /**
     * Update a project
     * 
     * @param int $projectId Project ID
     * @param string $userEmail User email (for permission checking)
     * @param array $data Project data to update
     * @return bool True on success, false on failure
     */
    public function updateProject($projectId, $userEmail, $data) {
        // First check if the project exists and belongs to the user
        $project = $this->getProjectById($projectId, $userEmail);
        if (!$project) {
            return false;
        }
        
        // Build the SQL query dynamically based on the provided data
        $fields = [];
        $values = [];
        
        foreach ($data as $key => $value) {
            if (in_array($key, ['title', 'description', 'client_name', 'budget', 'status', 'priority', 'start_date', 'end_date'])) {
                $fields[] = "$key = ?";
                $values[] = $value;
            }
        }
        
        if (empty($fields)) {
            return false; // No valid fields to update
        }
        
        // Add the project ID and user email to the values array
        $values[] = $projectId;
        $values[] = $userEmail;
        
        $sql = "UPDATE projects SET " . implode(', ', $fields) . " WHERE id = ? AND user_email = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute($values);
    }

    /**
     * Delete a project
     * 
     * @param int $projectId Project ID
     * @param string $userEmail User email (for permission checking)
     * @return bool True on success, false on failure
     */
    public function deleteProject($projectId, $userEmail) {
        $sql = "DELETE FROM projects WHERE id = ? AND user_email = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$projectId, $userEmail]);
    }

    /**
     * Change project status
     * 
     * @param int $projectId Project ID
     * @param string $status New status
     * @param string $userEmail User email (for permission checking)
     * @return bool True on success, false on failure
     */
    public function updateProjectStatus($projectId, $status, $userEmail) {
        $sql = "UPDATE projects SET status = ? WHERE id = ? AND user_email = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$status, $projectId, $userEmail]);
    }

    /**
     * Add a task to a project
     * 
     * @param int $projectId Project ID
     * @param string $title Task title
     * @param string $description Task description
     * @param string $assignedTo Email of user assigned to the task
     * @param string $dueDate Due date for the task
     * @return int|bool New task ID or false on failure
     */
    public function addTask($projectId, $title, $description, $assignedTo = null, $dueDate = null) {
        $sql = "INSERT INTO project_tasks (project_id, title, description, assigned_to, due_date) 
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $result = $stmt->execute([$projectId, $title, $description, $assignedTo, $dueDate]);
        
        if ($result) {
            return $this->db->lastInsertId();
        }
        
        return false;
    }

    /**
     * Get tasks for a project
     * 
     * @param int $projectId Project ID
     * @return array Array of tasks
     */
    public function getProjectTasks($projectId) {
        $sql = "SELECT * FROM project_tasks WHERE project_id = ? ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$projectId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get project statistics for a user
     * 
     * @param string $userEmail User email
     * @return array Project statistics
     */
    public function getProjectStats($userEmail = null) {
        $stats = [
            'total' => 0,
            'in_progress' => 0,
            'completed' => 0,
            'pending' => 0,
            'total_budget' => 0
        ];

        // Si aucun email n'est fourni, obtenir les stats pour tous les projets
        $whereClause = $userEmail ? "WHERE user_email = ?" : "";
        $params = $userEmail ? [$userEmail] : [];

        // Total projects
        $sql = "SELECT COUNT(*) as total FROM projects " . $whereClause;
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $stats['total'] = $stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0;
        
        // In-progress projects
        $sql = "SELECT COUNT(*) as in_progress FROM projects " . 
               ($whereClause ? $whereClause . " AND " : "WHERE ") . 
               "status = 'in-progress'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $stats['in_progress'] = $stmt->fetch(PDO::FETCH_ASSOC)['in_progress'] ?? 0;
        
        // Completed projects
        $sql = "SELECT COUNT(*) as completed FROM projects " . 
               ($whereClause ? $whereClause . " AND " : "WHERE ") . 
               "status = 'completed'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $stats['completed'] = $stmt->fetch(PDO::FETCH_ASSOC)['completed'] ?? 0;
        
        // Pending projects
        $sql = "SELECT COUNT(*) as pending FROM projects " . 
               ($whereClause ? $whereClause . " AND " : "WHERE ") . 
               "status = 'pending'";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $stats['pending'] = $stmt->fetch(PDO::FETCH_ASSOC)['pending'] ?? 0;
        
        // Total budget
        $sql = "SELECT SUM(budget) as total_budget FROM projects " . $whereClause;
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        $stats['total_budget'] = $stmt->fetch(PDO::FETCH_ASSOC)['total_budget'] ?? 0;
        
        return $stats;
    }

    public function hasApplied($projectId, $userEmail) {
        try {
            $sql = "SELECT id FROM candidatures 
                    WHERE project_id = ? 
                    AND user_email = ? 
                    AND status IN ('pending', 'accepted')
                    LIMIT 1";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$projectId, $userEmail]);
            return $stmt->fetch() ? true : false;
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function addCandidature($projectId, $userEmail, $message, $budget, $cvPath) {
        try {
            // Démarrer la transaction
            $this->db->beginTransaction();
            
            // Vérifier si le projet existe et est toujours ouvert
            $sql = "SELECT status FROM projects WHERE id = ? FOR UPDATE";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$projectId]);
            $project = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$project || $project['status'] !== 'pending') {
                $this->db->rollBack();
                return false;
            }
            
            // Insérer la nouvelle candidature avec le CV - l'expiration est définie à 48h par défaut dans la base de données
            $sql = "INSERT INTO candidatures (project_id, user_email, message, budget, cv_path, status, created_at) 
                    VALUES (?, ?, ?, ?, ?, 'pending', NOW())";
            $stmt = $this->db->prepare($sql);
            $result = $stmt->execute([$projectId, $userEmail, $message, $budget, $cvPath]);
            
            if ($result) {
                $candidatureId = $this->db->lastInsertId();
                $this->db->commit();
                error_log("addCandidature: Candidature créée avec ID: $candidatureId");
                return $candidatureId;
            }
            
            $this->db->rollBack();
            return false;
            
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log("addCandidature: Exception - " . $e->getMessage());
            return false;
        }
    }

    public function getProjectCandidatures($projectId) {
        $sql = "SELECT c.*, u.first_name, u.last_name, u.profile_image 
                FROM candidatures c 
                JOIN users u ON c.user_email = u.email 
                WHERE c.project_id = ? 
                ORDER BY c.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$projectId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateCandidatureStatus($candidatureId, $status) {
        $sql = "UPDATE candidatures SET status = ? WHERE id = ?";
        $stmt = $this->db->prepare($sql);
        return $stmt->execute([$status, $candidatureId]);
    }

    public function checkExpiredCandidatures() {
        try {
            // Mettre à jour les candidatures expirées
            $sql = "UPDATE candidatures 
                    SET status = 'expired' 
                    WHERE status = 'pending' 
                    AND created_at < (NOW() - INTERVAL 2 DAY)";
            $stmt = $this->db->prepare($sql);
            return $stmt->execute();
        } catch (Exception $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getRemainingTime($candidatureId) {
        try {
            $sql = "SELECT created_at FROM candidatures WHERE id = ?";
            $stmt = $this->db->prepare($sql);
            $stmt->execute([$candidatureId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$result) return null;
            
            $createdAt = new DateTime($result['created_at']);
            $expiresAt = clone $createdAt;
            $expiresAt->modify('+2 days');
            $now = new DateTime();
            
            if ($now > $expiresAt) {
                return 0;
            }
            
            return $expiresAt->getTimestamp() - $now->getTimestamp();
        } catch (Exception $e) {
            error_log($e->getMessage());
            return null;
        }
    }

    public function getAvailableProjects($userEmail, $limit = null) {
        if ($userEmail === null) {
            // Si aucun email d'utilisateur n'est fourni, récupère tous les projets actifs
            $sql = "SELECT p.*, u.first_name, u.last_name 
                    FROM projects p 
                    JOIN users u ON p.user_email = u.email
                    WHERE p.status = 'pending'
                    ORDER BY p.created_at DESC";
            
            if ($limit !== null) {
                $sql .= " LIMIT ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$limit]);
            } else {
                $stmt = $this->db->prepare($sql);
                $stmt->execute();
            }
        } else {
            // Si un email est fourni, exclut les projets de cet utilisateur
            $sql = "SELECT p.*, u.first_name, u.last_name 
                    FROM projects p 
                    JOIN users u ON p.user_email = u.email
                    WHERE p.user_email != ? 
                    AND p.status = 'pending'
                    ORDER BY p.created_at DESC";
            
            if ($limit !== null) {
                $sql .= " LIMIT ?";
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$userEmail, $limit]);
            } else {
                $stmt = $this->db->prepare($sql);
                $stmt->execute([$userEmail]);
            }
        }
        
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserCandidatures($userEmail) {
        $sql = "SELECT c.*, p.title as project_title 
                FROM candidatures c 
                JOIN projects p ON c.project_id = p.id
                WHERE c.user_email = ? 
                ORDER BY c.created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([$userEmail]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}