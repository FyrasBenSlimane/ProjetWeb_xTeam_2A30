<?php
class Participant {
    private $db;
    private $table = 'participants';

    public function __construct($database) {
        $this->db = $database;
    }

    public function getAll($filters = []) {
        $query = "SELECT p.*, e.title as event_title 
                 FROM {$this->table} p 
                 JOIN events e ON p.event_id = e.id";
        
        $conditions = [];
        $values = [];

        if (!empty($filters['event_id'])) {
            $conditions[] = "p.event_id = ?";
            $values[] = $filters['event_id'];
        }

        if (!empty($filters['status'])) {
            $conditions[] = "p.status = ?";
            $values[] = $filters['status'];
        }

        if (!empty($conditions)) {
            $query .= " WHERE " . implode(' AND ', $conditions);
        }

        $query .= " ORDER BY p.registration_date DESC";

        $stmt = $this->db->prepare($query);
        $stmt->execute($values);
        return $stmt->fetchAll();
    }

    public function create($data) {
        $query = "INSERT INTO {$this->table} (name, email, phone, event_id, status) 
                 VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $data['name'],
            $data['email'],
            $data['phone'] ?? null,
            $data['event_id'],
            $data['status'] ?? 'pending'
        ]);
    }

    public function updateStatus($id, $status, $adminNotes = null) {
        $query = "UPDATE {$this->table} SET status = ?, admin_notes = ? WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$status, $adminNotes, $id]);
    }

    public function getRecentParticipants($limit = 5) {
        $query = "SELECT p.*, e.title as event_title 
                 FROM {$this->table} p 
                 JOIN events e ON p.event_id = e.id 
                 ORDER BY p.registration_date DESC 
                 LIMIT ?";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }

    public function getParticipantsByEventId($eventId) {
        $query = "SELECT * FROM {$this->table} WHERE event_id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$eventId]);
        return $stmt->fetchAll();
    }

    public function getStats() {
        $query = "SELECT 
                    COUNT(*) as total,
                    SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending,
                    SUM(CASE WHEN status = 'confirmed' THEN 1 ELSE 0 END) as confirmed,
                    SUM(CASE WHEN status = 'rejected' THEN 1 ELSE 0 END) as rejected
                 FROM {$this->table}";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetch();
    }
}