<?php
// Support Model
// Handles database interactions for the support ticket system
class Support
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    // Create the necessary tables if they don't exist
    private function ensureTablesExist()
    {
        // Check if ticket_responses table exists
        $this->db->query("SHOW TABLES LIKE 'ticket_responses'");
        $tableExists = $this->db->resultSet();

        if (empty($tableExists)) {
            // Create ticket_responses table if it doesn't exist
            $this->db->query("CREATE TABLE IF NOT EXISTS ticket_responses (
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
        }
    }

    // Create a new support ticket
    public function createTicket($data)
    {
        $this->ensureTablesExist();

        $this->db->query('INSERT INTO support_tickets (subject, description, user_id, category, priority, status) 
                          VALUES(:subject, :description, :user_id, :category, :priority, :status)');

        // Bind values
        $this->db->bind(':subject', $data['subject']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':priority', $data['priority']);
        $this->db->bind(':status', 'open'); // Default status for new tickets

        // Execute
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        } else {
            return false;
        }
    }
    // Get all tickets for a specific user
    public function getTicketsByUserId($userId)
    {
        if (empty($userId)) {
            error_log('Warning: Empty user ID passed to getTicketsByUserId');
            return [];
        }

        try {
            $this->ensureTablesExist();

            $this->db->query('SELECT * FROM support_tickets WHERE user_id = :user_id ORDER BY 
                             CASE 
                                WHEN status = "open" THEN 1
                                WHEN status = "awaiting-response" THEN 2
                                WHEN status = "in-progress" THEN 3
                                WHEN status = "resolved" THEN 4
                                WHEN status = "closed" THEN 5
                             END,
                             created_at DESC');

            $this->db->bind(':user_id', $userId);
            $results = $this->db->resultSet();

            return $results;
        } catch (Exception $e) {
            error_log('Error in getTicketsByUserId: ' . $e->getMessage());
            throw $e; // Re-throw to handle in controller
        }
    }

    // Get a specific ticket by ID
    public function getTicketById($ticketId)
    {
        $this->ensureTablesExist();

        $this->db->query('SELECT t.*, u.name as user_name, u.email as user_email 
                          FROM support_tickets t
                          LEFT JOIN users u ON t.user_id = u.id
                          WHERE t.id = :id');

        $this->db->bind(':id', $ticketId);

        $row = $this->db->single();

        return $row;
    }

    // Get responses for a specific ticket
    public function getTicketResponses($ticketId)
    {
        $this->ensureTablesExist();

        $this->db->query('SELECT r.*, 
                         CASE 
                            WHEN r.is_staff = 1 THEN "Support Team" 
                            ELSE u.name 
                         END as respondent_name
                         FROM ticket_responses r
                         LEFT JOIN users u ON r.user_id = u.id
                         WHERE r.ticket_id = :ticket_id
                         ORDER BY r.created_at ASC');

        $this->db->bind(':ticket_id', $ticketId);

        $results = $this->db->resultSet();

        return $results;
    }

    // Add a response to a ticket
    public function addTicketResponse($ticketId, $message, $userId, $isStaff = false)
    {
        $this->ensureTablesExist();

        $this->db->query('INSERT INTO ticket_responses (ticket_id, user_id, message, is_staff) 
                          VALUES(:ticket_id, :user_id, :message, :is_staff)');

        // Bind values
        $this->db->bind(':ticket_id', $ticketId);
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':message', $message);
        $this->db->bind(':is_staff', $isStaff ? 1 : 0);

        // Execute
        if ($this->db->execute()) {
            // Update the ticket's updated_at timestamp
            $this->db->query('UPDATE support_tickets SET updated_at = CURRENT_TIMESTAMP WHERE id = :id');
            $this->db->bind(':id', $ticketId);
            $this->db->execute();

            return true;
        } else {
            return false;
        }
    }

    // Update ticket status
    public function updateTicketStatus($ticketId, $status)
    {
        $this->ensureTablesExist();

        $this->db->query('UPDATE support_tickets SET status = :status, updated_at = CURRENT_TIMESTAMP WHERE id = :id');

        // Bind values
        $this->db->bind(':id', $ticketId);
        $this->db->bind(':status', $status);

        // Execute
        return $this->db->execute();
    }

    // Get tickets count by status for a user
    public function getTicketsCountByStatus($userId)
    {
        $this->ensureTablesExist();

        $this->db->query('SELECT status, COUNT(*) as count FROM support_tickets 
                          WHERE user_id = :user_id 
                          GROUP BY status');

        $this->db->bind(':user_id', $userId);

        $results = $this->db->resultSet();
        $counts = [
            'open' => 0,
            'in-progress' => 0,
            'awaiting-response' => 0,
            'resolved' => 0,
            'closed' => 0,
            'total' => 0
        ];

        foreach ($results as $row) {
            $counts[$row->status] = $row->count;
            $counts['total'] += $row->count;
        }

        return $counts;
    }

    // For admin: Get all tickets
    public function getAllTickets($limit = null, $offset = null, $status = null)
    {
        $this->ensureTablesExist();

        $query = 'SELECT t.*, u.name as user_name, u.email as user_email 
                  FROM support_tickets t
                  LEFT JOIN users u ON t.user_id = u.id';

        // Add status filter if provided
        if ($status) {
            $query .= ' WHERE t.status = :status';
        }

        // Add order by
        $query .= ' ORDER BY 
                   CASE 
                       WHEN t.status = "open" THEN 1
                       WHEN t.status = "awaiting-response" THEN 2
                       WHEN t.status = "in-progress" THEN 3
                       WHEN t.status = "resolved" THEN 4
                       WHEN t.status = "closed" THEN 5
                   END,
                   CASE 
                       WHEN t.priority = "critical" THEN 1
                       WHEN t.priority = "high" THEN 2
                       WHEN t.priority = "medium" THEN 3
                       WHEN t.priority = "low" THEN 4
                   END,
                   t.created_at DESC';

        // Add limits if provided
        if ($limit !== null) {
            $query .= ' LIMIT :limit';
            if ($offset !== null) {
                $query .= ' OFFSET :offset';
            }
        }

        $this->db->query($query);

        // Bind status if provided
        if ($status) {
            $this->db->bind(':status', $status);
        }

        // Bind limits if provided
        if ($limit !== null) {
            $this->db->bind(':limit', $limit, PDO::PARAM_INT);
            if ($offset !== null) {
                $this->db->bind(':offset', $offset, PDO::PARAM_INT);
            }
        }

        $results = $this->db->resultSet();

        return $results;
    }

    // Count tickets for pagination
    public function countAllTickets($status = null)
    {
        $this->ensureTablesExist();

        $query = 'SELECT COUNT(*) as total FROM support_tickets';

        if ($status) {
            $query .= ' WHERE status = :status';
        }

        $this->db->query($query);

        if ($status) {
            $this->db->bind(':status', $status);
        }

        $row = $this->db->single();

        return $row->total;
    }

    // Get ticket activity for dashboard
    public function getTicketActivity($limit = 5)
    {
        $this->ensureTablesExist();

        $this->db->query('SELECT t.id, t.subject, t.status, t.priority, t.created_at, t.updated_at, 
                         u.name as user_name, COUNT(r.id) as response_count
                         FROM support_tickets t
                         LEFT JOIN users u ON t.user_id = u.id
                         LEFT JOIN ticket_responses r ON t.id = r.ticket_id
                         GROUP BY t.id
                         ORDER BY t.updated_at DESC
                         LIMIT :limit');

        $this->db->bind(':limit', $limit, PDO::PARAM_INT);

        $results = $this->db->resultSet();

        return $results;
    }

    // Search tickets
    public function searchTickets($search, $userId = null)
    {
        $this->ensureTablesExist();

        $query = 'SELECT t.*, u.name as user_name, u.email as user_email 
                  FROM support_tickets t
                  LEFT JOIN users u ON t.user_id = u.id
                  WHERE (t.subject LIKE :search OR t.description LIKE :search)';

        if ($userId !== null) {
            $query .= ' AND t.user_id = :user_id';
        }

        $query .= ' ORDER BY t.updated_at DESC';

        $this->db->query($query);

        $this->db->bind(':search', '%' . $search . '%');

        if ($userId !== null) {
            $this->db->bind(':user_id', $userId);
        }

        $results = $this->db->resultSet();

        return $results;
    }

    // Delete a ticket
    public function deleteTicket($ticketId)
    {
        $this->ensureTablesExist();

        // First delete all responses related to this ticket
        $this->db->query('DELETE FROM ticket_responses WHERE ticket_id = :ticket_id');
        $this->db->bind(':ticket_id', $ticketId);
        $this->db->execute();

        // Then delete the ticket itself
        $this->db->query('DELETE FROM support_tickets WHERE id = :id');
        $this->db->bind(':id', $ticketId);

        return $this->db->execute();
    }

    // Update ticket details
    public function updateTicket($data)
    {
        $this->ensureTablesExist();

        $this->db->query('UPDATE support_tickets 
                         SET subject = :subject, 
                             description = :description, 
                             category = :category, 
                             priority = :priority,
                             updated_at = CURRENT_TIMESTAMP 
                         WHERE id = :id AND user_id = :user_id');

        // Bind values
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':subject', $data['subject']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':priority', $data['priority']);

        return $this->db->execute();
    }

    // Check if a ticket has any admin responses
    public function hasAdminResponses($ticketId)
    {
        $this->ensureTablesExist();

        $this->db->query('SELECT COUNT(*) as count FROM ticket_responses WHERE ticket_id = :ticket_id AND is_staff = 1');
        $this->db->bind(':ticket_id', $ticketId);

        $row = $this->db->single();
        return ($row->count > 0);
    }
}
