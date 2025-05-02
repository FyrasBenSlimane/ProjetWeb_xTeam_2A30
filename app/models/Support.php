<?php
/**
 * Support Model
 * Handles database operations for support tickets, replies, and statistics
 */
class Support
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    /**
     * Get all tickets for a specific user
     * 
     * @param int $user_id The user ID
     * @return array|false Array of tickets or false if none
     */
    public function getTicketsByUser($user_id)
    {
        $this->db->query('SELECT * FROM support_tickets WHERE user_id = :user_id ORDER BY created_at DESC');
        $this->db->bind(':user_id', $user_id);

        return $this->db->resultSet();
    }

    /**
     * Get a ticket by ID
     * 
     * @param int $id The ticket ID
     * @return object|false Ticket object or false
     */
    public function getTicketById($id)
    {
        $this->db->query('SELECT t.*, u.name AS user_name, u.email AS user_email
                          FROM support_tickets t
                          JOIN users u ON t.user_id = u.id
                          WHERE t.id = :id');
        $this->db->bind(':id', $id);

        return $this->db->single();
    }

    /**
     * Get all replies for a ticket
     * 
     * @param int $ticket_id The ticket ID
     * @return array|false Array of replies or false if none
     */
    public function getTicketReplies($ticket_id)
    {
        $this->db->query('SELECT r.*, u.name AS user_name, u.profile_image
                          FROM support_replies r
                          JOIN users u ON r.user_id = u.id
                          WHERE r.ticket_id = :ticket_id
                          ORDER BY r.created_at ASC');
        $this->db->bind(':ticket_id', $ticket_id);

        return $this->db->resultSet();
    }

    /**
     * Create a new support ticket
     * 
     * @param array $data The ticket data
     * @return bool Success or failure
     */
    public function createTicket($data)
    {
        // Set status based on whether this is a draft or not
        $status = isset($data['is_draft']) && $data['is_draft'] ? 'draft' : 'open';

        $this->db->query('INSERT INTO support_tickets (user_id, subject, description, category, priority, status, attachment_filename)
                          VALUES(:user_id, :subject, :description, :category, :priority, :status, :attachment_filename)');

        // Bind values
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':subject', $data['subject']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':priority', $data['priority']);
        $this->db->bind(':status', $status);
        $this->db->bind(':attachment_filename', $data['attachment_filename']); // Bind attachment filename

        // Execute
        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }
        return false;
    }

    /**
     * Add a reply to a ticket
     * 
     * @param array $data The reply data
     * @return bool Success or failure
     */
    public function addReply($data)
    {
        $this->db->query('INSERT INTO support_replies (ticket_id, user_id, message, is_admin)
                          VALUES(:ticket_id, :user_id, :message, :is_admin)');

        // Bind values
        $this->db->bind(':ticket_id', $data['ticket_id']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':message', $data['message']);
        $this->db->bind(':is_admin', $data['is_admin']);

        // Execute
        return $this->db->execute();
    }

    /**
     * Update ticket status
     * 
     * @param int $id The ticket ID
     * @param string $status The new status
     * @return bool Success or failure
     */
    public function updateTicketStatus($id, $status)
    {
        $this->db->query('UPDATE support_tickets SET status = :status, updated_at = NOW() 
                         WHERE id = :id');

        // Bind values
        $this->db->bind(':id', $id);
        $this->db->bind(':status', $status);

        // Execute
        return $this->db->execute();
    }

    /**
     * Count all tickets for a specific user
     * 
     * @param int $user_id The user ID
     * @return int Number of tickets
     */
    public function countTickets($user_id)
    {
        $this->db->query('SELECT COUNT(*) as count FROM support_tickets WHERE user_id = :user_id');
        $this->db->bind(':user_id', $user_id);

        $result = $this->db->single();
        return $result->count ?? 0;
    }

    /**
     * Count open tickets for a specific user
     * 
     * @param int $user_id The user ID
     * @return int Number of open tickets
     */
    public function countOpenTickets($user_id)
    {
        $this->db->query('SELECT COUNT(*) as count FROM support_tickets 
                          WHERE user_id = :user_id AND status != "closed"');
        $this->db->bind(':user_id', $user_id);

        $result = $this->db->single();
        return $result->count ?? 0;
    }

    /**
     * Delete a ticket and its replies
     * 
     * @param int $id The ticket ID
     * @return bool Success or failure
     */
    public function deleteTicket($id)
    {
        // First delete all replies associated with this ticket
        $this->db->query('DELETE FROM support_replies WHERE ticket_id = :id');
        $this->db->bind(':id', $id);
        $this->db->execute();

        // Then delete the ticket
        $this->db->query('DELETE FROM support_tickets WHERE id = :id');
        $this->db->bind(':id', $id);

        // Execute
        return $this->db->execute();
    }

    /**
     * Update a ticket
     * 
     * @param array $data The ticket data
     * @return bool Success or failure
     */
    public function updateTicket($data)
    {
        $this->db->query('UPDATE support_tickets SET 
                          subject = :subject, 
                          description = :description, 
                          category = :category, 
                          priority = :priority, 
                          updated_at = NOW() 
                          WHERE id = :id');

        // Bind values
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':subject', $data['subject']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':priority', $data['priority']);

        // Execute
        return $this->db->execute();
    }

    /**
     * Update a draft ticket, including status and attachment
     * 
     * @param array $data The ticket data
     * @return bool Success or failure
     */
    public function updateTicketDraft($data)
    {
        $this->db->query('UPDATE support_tickets SET 
                          subject = :subject, 
                          description = :description, 
                          category = :category, 
                          priority = :priority, 
                          status = :status, 
                          attachment_filename = :attachment_filename, 
                          updated_at = NOW() 
                          WHERE id = :id AND user_id = :user_id'); // Added user_id check for security

        // Bind values
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':subject', $data['subject']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':priority', $data['priority']);
        $this->db->bind(':status', $data['status']); // Update status (could become 'open')
        $this->db->bind(':attachment_filename', $data['attachment_filename']);
        $this->db->bind(':user_id', $_SESSION['user_id']); // Ensure user owns the draft

        // Execute
        return $this->db->execute();
    }

    /**
     * Get all draft tickets for a specific user
     * 
     * @param int $user_id The user ID
     * @return array|false Array of draft tickets or false if none
     */
    public function getDraftTicketsByUser($user_id)
    {
        $this->db->query('SELECT * FROM support_tickets WHERE user_id = :user_id AND status = "draft" ORDER BY created_at DESC');
        $this->db->bind(':user_id', $user_id);

        return $this->db->resultSet();
    }

    /**
     * Convert a draft ticket to a regular ticket
     * 
     * @param int $id The ticket ID
     * @return bool Success or failure
     */
    public function submitDraftTicket($id)
    {
        $this->db->query('UPDATE support_tickets SET status = "open", updated_at = NOW() WHERE id = :id AND status = "draft"');
        $this->db->bind(':id', $id);

        return $this->db->execute();
    }

    /**
     * Count draft tickets for a specific user
     * 
     * @param int $user_id The user ID
     * @return int Number of draft tickets
     */
    public function countDraftTickets($user_id)
    {
        $this->db->query('SELECT COUNT(*) as count FROM support_tickets WHERE user_id = :user_id AND status = "draft"');
        $this->db->bind(':user_id', $user_id);

        $result = $this->db->single();
        return $result->count ?? 0;
    }

    /**
     * Get all tickets (admin function)
     * 
     * @param string $status Optional status filter
     * @param int $limit Optional limit
     * @param int $offset Optional offset for pagination
     * @return array|false Array of tickets or false if none
     */
    public function getAllTickets($status = null, $limit = null, $offset = 0)
    {
        $sql = 'SELECT t.*, u.name AS user_name, u.email AS user_email
                FROM support_tickets t
                JOIN users u ON t.user_id = u.id';
        
        if ($status) {
            $sql .= ' WHERE t.status = :status';
        }
        
        $sql .= ' ORDER BY t.created_at DESC';
        
        if ($limit) {
            $sql .= ' LIMIT :limit OFFSET :offset';
        }
        
        $this->db->query($sql);
        
        if ($status) {
            $this->db->bind(':status', $status);
        }
        
        if ($limit) {
            $this->db->bind(':limit', $limit, PDO::PARAM_INT);
            $this->db->bind(':offset', $offset, PDO::PARAM_INT);
        }
        
        return $this->db->resultSet();
    }

    /**
     * Count tickets by status (admin function)
     * 
     * @param string $status The status to count
     * @return int Number of tickets
     */
    public function countTicketsByStatus($status)
    {
        $this->db->query('SELECT COUNT(*) as count FROM support_tickets WHERE status = :status');
        $this->db->bind(':status', $status);

        $result = $this->db->single();
        return $result->count ?? 0;
    }

    /**
     * Get ticket statistics for dashboard
     * 
     * @return array Ticket statistics
     */
    public function getTicketStatistics()
    {
        $stats = [
            'total' => 0,
            'open' => 0,
            'in_progress' => 0,
            'closed' => 0,
            'pending' => 0,
            'response_time' => 0
        ];
        
        // Get total tickets
        $this->db->query('SELECT COUNT(*) as count FROM support_tickets WHERE status != "draft"');
        $result = $this->db->single();
        $stats['total'] = $result->count ?? 0;
        
        // Get open tickets
        $this->db->query('SELECT COUNT(*) as count FROM support_tickets WHERE status = "open"');
        $result = $this->db->single();
        $stats['open'] = $result->count ?? 0;
        
        // Get in-progress tickets
        $this->db->query('SELECT COUNT(*) as count FROM support_tickets WHERE status = "in_progress"');
        $result = $this->db->single();
        $stats['in_progress'] = $result->count ?? 0;
        
        // Get closed tickets
        $this->db->query('SELECT COUNT(*) as count FROM support_tickets WHERE status = "closed"');
        $result = $this->db->single();
        $stats['closed'] = $result->count ?? 0;
        
        // Get pending tickets
        $this->db->query('SELECT COUNT(*) as count FROM support_tickets WHERE status = "pending"');
        $result = $this->db->single();
        $stats['pending'] = $result->count ?? 0;
        
        // Calculate average response time (first admin reply after ticket creation)
        $this->db->query('SELECT AVG(TIMESTAMPDIFF(HOUR, t.created_at, MIN(r.created_at))) as avg_time
                          FROM support_tickets t
                          JOIN support_replies r ON t.id = r.ticket_id
                          WHERE r.is_admin = 1
                          GROUP BY t.id');
        $result = $this->db->single();
        $stats['response_time'] = round($result->avg_time ?? 0, 1);
        
        return $stats;
    }

    /**
     * Assign a ticket to an admin
     * 
     * @param int $ticket_id The ticket ID
     * @param int $admin_id The admin user ID
     * @return bool Success or failure
     */
    public function assignTicket($ticket_id, $admin_id)
    {
        $this->db->query('UPDATE support_tickets SET assigned_to = :admin_id, status = "in_progress", updated_at = NOW() 
                         WHERE id = :ticket_id');

        // Bind values
        $this->db->bind(':ticket_id', $ticket_id);
        $this->db->bind(':admin_id', $admin_id);

        // Execute
        return $this->db->execute();
    }

    /**
     * Get tickets assigned to a specific admin
     * 
     * @param int $admin_id The admin user ID
     * @return array|false Array of tickets or false if none
     */
    public function getAssignedTickets($admin_id)
    {
        $this->db->query('SELECT t.*, u.name AS user_name, u.email AS user_email
                          FROM support_tickets t
                          JOIN users u ON t.user_id = u.id
                          WHERE t.assigned_to = :admin_id
                          ORDER BY t.created_at DESC');
        $this->db->bind(':admin_id', $admin_id);

        return $this->db->resultSet();
    }

    /**
     * Mark a ticket as resolved/closed
     * 
     * @param int $ticket_id The ticket ID
     * @param string $resolution Optional resolution notes
     * @return bool Success or failure
     */
    public function resolveTicket($ticket_id, $resolution = null)
    {
        $this->db->query('UPDATE support_tickets SET status = "closed", resolution = :resolution, closed_at = NOW(), updated_at = NOW() 
                         WHERE id = :ticket_id');

        // Bind values
        $this->db->bind(':ticket_id', $ticket_id);
        $this->db->bind(':resolution', $resolution);

        // Execute
        return $this->db->execute();
    }

    /**
     * Get recent tickets for dashboard
     * 
     * @param int $limit Number of tickets to return
     * @return array|false Array of tickets or false if none
     */
    public function getRecentTickets($limit = 5)
    {
        $this->db->query('SELECT t.*, u.name AS user_name, u.email AS user_email
                          FROM support_tickets t
                          JOIN users u ON t.user_id = u.id
                          WHERE t.status != "draft"
                          ORDER BY t.created_at DESC
                          LIMIT :limit');
        $this->db->bind(':limit', $limit, PDO::PARAM_INT);

        return $this->db->resultSet();
    }
}
