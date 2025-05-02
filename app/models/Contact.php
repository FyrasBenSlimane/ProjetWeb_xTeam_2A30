<?php
class Contact
{
    private $db;

    public function __construct()
    {
        $this->db = new Database;
    }

    /**
     * Submit a contact message
     * @param array $data The contact message data
     * @return bool True on success, false on failure
     */
    public function submitContactMessage($data)
    {
        // Prepare the SQL statement
        $this->db->query('INSERT INTO contact_messages (user_id, name, email, subject, message, inquiry_type, priority, browser_info, billing_details, business_details) 
                         VALUES(:user_id, :name, :email, :subject, :message, :inquiry_type, :priority, :browser_info, :billing_details, :business_details)');

        // Bind values
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':subject', $data['subject']);
        $this->db->bind(':message', $data['message']);
        $this->db->bind(':inquiry_type', $data['inquiry_type']);
        $this->db->bind(':priority', $data['priority'] ?? 'medium');
        $this->db->bind(':browser_info', $data['browser_info'] ?? null);
        $this->db->bind(':billing_details', $data['billing_details'] ?? null);
        $this->db->bind(':business_details', $data['business_details'] ?? null);

        // Execute
        if ($this->db->execute()) {
            return $this->db->lastId();
        } else {
            return false;
        }
    }

    /**
     * Get a single contact message by ID
     * @param int $id Contact message ID
     * @return object|bool Contact message object or false if not found
     */
    public function getContactMessageById($id)
    {
        $this->db->query('SELECT * FROM contact_messages WHERE id = :id');
        $this->db->bind(':id', $id);

        $row = $this->db->single();

        return $row;
    }

    /**
     * Get all contact messages
     * @param array $filters Optional filters like status, inquiry_type, etc.
     * @return array Array of contact messages
     */
    public function getAllContactMessages($filters = [])
    {
        $sql = 'SELECT * FROM contact_messages';

        // Apply filters if provided
        $whereClauses = [];
        if (!empty($filters)) {
            foreach ($filters as $key => $value) {
                if ($value !== null && $value !== '') {
                    $whereClauses[] = $key . ' = :' . $key;
                }
            }

            if (!empty($whereClauses)) {
                $sql .= ' WHERE ' . implode(' AND ', $whereClauses);
            }
        }

        // Add ordering
        $sql .= ' ORDER BY created_at DESC';

        $this->db->query($sql);

        // Bind filter values if any
        if (!empty($whereClauses) && !empty($filters)) {
            foreach ($filters as $key => $value) {
                if ($value !== null && $value !== '') {
                    $this->db->bind(':' . $key, $value);
                }
            }
        }

        return $this->db->resultSet();
    }

    /**
     * Get contact messages for a specific user
     * @param int $userId User ID
     * @return array Array of contact messages
     */
    public function getContactMessagesByUser($userId)
    {
        $this->db->query('SELECT * FROM contact_messages WHERE user_id = :user_id ORDER BY created_at DESC');
        $this->db->bind(':user_id', $userId);

        return $this->db->resultSet();
    }

    /**
     * Update contact message status
     * @param int $id Contact message ID
     * @param string $status New status
     * @return bool True on success, false on failure
     */
    public function updateMessageStatus($id, $status)
    {
        $this->db->query('UPDATE contact_messages SET status = :status WHERE id = :id');
        $this->db->bind(':id', $id);
        $this->db->bind(':status', $status);

        return $this->db->execute();
    }

    /**
     * Mark a contact message as read
     * @param int $id Contact message ID
     * @return bool True on success, false on failure
     */
    public function markAsRead($id)
    {
        $this->db->query('UPDATE contact_messages SET is_read = true WHERE id = :id');
        $this->db->bind(':id', $id);

        return $this->db->execute();
    }

    /**
     * Get count of unread contact messages
     * @return int Number of unread messages
     */
    public function getUnreadCount()
    {
        $this->db->query('SELECT COUNT(*) as count FROM contact_messages WHERE is_read = false');
        $result = $this->db->single();

        return $result->count;
    }

    /**
     * Delete a contact message
     * @param int $id Contact message ID
     * @return bool True on success, false on failure
     */
    public function deleteContactMessage($id)
    {
        $this->db->query('DELETE FROM contact_messages WHERE id = :id');
        $this->db->bind(':id', $id);

        return $this->db->execute();
    }
}
