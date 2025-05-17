<?php
/**
 * EventsModel Class
 * Handles database operations for events and registrations
 */
class EventsModel {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    /**
     * Get all published events
     * 
     * @return array List of published events
     */
    public function getPublishedEvents() {
        $this->db->query("SELECT e.*, COUNT(er.id) as registrations_count 
                         FROM events e 
                         LEFT JOIN event_registrations er ON e.id = er.event_id 
                         WHERE e.status = 'published' 
                         GROUP BY e.id 
                         ORDER BY e.start_date ASC");
        
        return $this->db->resultSet();
    }

    /**
     * Get all upcoming published events (events with start_date in the future)
     * 
     * @param int $limit Optional limit on the number of events to return
     * @return array List of upcoming events
     */
    public function getUpcomingEvents($limit = null) {
        $limitClause = $limit ? "LIMIT :limit" : "";
        
        $this->db->query("SELECT e.*, COUNT(er.id) as registrations_count 
                         FROM events e 
                         LEFT JOIN event_registrations er ON e.id = er.event_id 
                         WHERE e.status = 'published' AND e.start_date >= NOW() 
                         GROUP BY e.id 
                         ORDER BY e.start_date ASC 
                         $limitClause");
        
        if ($limit) {
            $this->db->bind(':limit', $limit, PDO::PARAM_INT);
        }
        
        return $this->db->resultSet();
    }

    /**
     * Get a single event by ID
     * 
     * @param int $id Event ID
     * @return object Event data
     */
    public function getEventById($id) {
        $this->db->query("SELECT e.*, u.name as creator_name, COUNT(er.id) as registrations_count 
                         FROM events e 
                         LEFT JOIN users u ON e.created_by = u.id 
                         LEFT JOIN event_registrations er ON e.id = er.event_id 
                         WHERE e.id = :id 
                         GROUP BY e.id");
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }

    /**
     * Get events by category
     * 
     * @param string $category Event category
     * @return array Events in the specified category
     */
    public function getEventsByCategory($category) {
        $this->db->query("SELECT e.*, COUNT(er.id) as registrations_count 
                         FROM events e 
                         LEFT JOIN event_registrations er ON e.id = er.event_id 
                         WHERE e.category = :category AND e.status = 'published' 
                         GROUP BY e.id 
                         ORDER BY e.start_date ASC");
        $this->db->bind(':category', $category);
        
        return $this->db->resultSet();
    }

    /**
     * Get events by type
     * 
     * @param string $type Event type
     * @return array Events of the specified type
     */
    public function getEventsByType($type) {
        $this->db->query("SELECT e.*, COUNT(er.id) as registrations_count 
                         FROM events e 
                         LEFT JOIN event_registrations er ON e.id = er.event_id 
                         WHERE e.event_type = :type AND e.status = 'published' 
                         GROUP BY e.id 
                         ORDER BY e.start_date ASC");
        $this->db->bind(':type', $type);
        
        return $this->db->resultSet();
    }

    /**
     * Get events for a specific date range
     * 
     * @param string $startDate Start date in Y-m-d format
     * @param string $endDate End date in Y-m-d format
     * @return array Events in the date range
     */
    public function getEventsByDateRange($startDate, $endDate) {
        $this->db->query("SELECT e.*, COUNT(er.id) as registrations_count 
                         FROM events e 
                         LEFT JOIN event_registrations er ON e.id = er.event_id 
                         WHERE e.start_date BETWEEN :start_date AND :end_date 
                         AND e.status = 'published' 
                         GROUP BY e.id 
                         ORDER BY e.start_date ASC");
        $this->db->bind(':start_date', $startDate);
        $this->db->bind(':end_date', $endDate);
        
        return $this->db->resultSet();
    }

    /**
     * Register a user for an event
     * 
     * @param array $data Registration data
     * @return bool True on success, false on failure
     */
    public function registerForEvent($data) {
        // Check if user is already registered for this event
        $this->db->query('SELECT id FROM event_registrations WHERE event_id = :event_id AND user_id = :user_id');
        $this->db->bind(':event_id', $data['event_id']);
        $this->db->bind(':user_id', $data['user_id']);
        
        $existing = $this->db->single();
        
        if ($existing) {
            // User is already registered
            return false;
        }
        
        // Register the user
        $this->db->query('INSERT INTO event_registrations (event_id, user_id, attendance_type, notes) 
                          VALUES (:event_id, :user_id, :attendance_type, :notes)');
        
        // Bind values
        $this->db->bind(':event_id', $data['event_id']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':attendance_type', $data['attendance_type']);
        $this->db->bind(':notes', $data['notes']);
        
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Get registrations for a user
     * 
     * @param int $userId User ID
     * @return array User's registrations
     */
    public function getUserRegistrations($userId) {
        $this->db->query("SELECT er.*, e.title as event_title, e.start_date 
                         FROM event_registrations er 
                         JOIN events e ON er.event_id = e.id 
                         WHERE er.user_id = :user_id 
                         ORDER BY e.start_date ASC");
        $this->db->bind(':user_id', $userId);
        
        return $this->db->resultSet();
    }

    /**
     * Check if a user is registered for an event
     * 
     * @param int $eventId Event ID
     * @param int $userId User ID
     * @return bool True if registered, false otherwise
     */
    public function isUserRegistered($eventId, $userId) {
        $this->db->query('SELECT id FROM event_registrations WHERE event_id = :event_id AND user_id = :user_id');
        $this->db->bind(':event_id', $eventId);
        $this->db->bind(':user_id', $userId);
        
        $this->db->single();
        
        return $this->db->rowCount() > 0;
    }
    
    /**
     * Get all registrations for an event
     * 
     * @param int $eventId Event ID
     * @return array Event registrations
     */
    public function getEventRegistrations($eventId) {
        $this->db->query("SELECT er.*, u.name as user_name
                         FROM event_registrations er
                         LEFT JOIN users u ON er.user_id = u.id
                         WHERE er.event_id = :event_id
                         ORDER BY er.created_at DESC");
        $this->db->bind(':event_id', $eventId);
        
        return $this->db->resultSet();
    }
    
    /**
     * Update registration status
     * 
     * @param int $registrationId Registration ID
     * @param string $status New status
     * @return bool True on success, false on failure
     */
    public function updateRegistrationStatus($registrationId, $status) {
        $this->db->query("UPDATE event_registrations SET status = :status, updated_at = NOW() WHERE id = :id");
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $registrationId);
        
        return $this->db->execute();
    }

    /**
     * Get specific registration details by its ID
     * 
     * @param int $registrationId Registration ID
     * @return object|false Registration data or false if not found
     */
    public function getRegistrationDetailsById($registrationId) {
        $this->db->query("SELECT er.*, u.email as user_email, u.name as user_name, e.title as event_title, e.start_date as event_start_date
                         FROM event_registrations er
                         JOIN users u ON er.user_id = u.id
                         JOIN events e ON er.event_id = e.id
                         WHERE er.id = :registration_id");
        $this->db->bind(':registration_id', $registrationId);
        
        $registration = $this->db->single();
        
        if ($this->db->rowCount() > 0) {
            return $registration;
        } else {
            return false;
        }
    }
} 