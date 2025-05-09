<?php
class Event {
    private $db;
    private $table = 'events';

    public function __construct($database) {
        $this->db = $database;
    }

    public function getAll() {
        $query = "SELECT e.*, COUNT(p.id) as participant_count 
                 FROM {$this->table} e 
                 LEFT JOIN participants p ON e.id = p.event_id 
                 GROUP BY e.id 
                 ORDER BY e.date DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $query = "SELECT * FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $query = "INSERT INTO {$this->table} (title, date, location, description, image) 
                 VALUES (?, ?, ?, ?, ?)";
        
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            $data['title'],
            $data['date'],
            $data['location'],
            $data['description'],
            $data['image']
        ]);
    }

    public function update($id, $data) {
        $fields = [];
        $values = [];

        foreach ($data as $key => $value) {
            if ($value !== null) {
                $fields[] = "$key = ?";
                $values[] = $value;
            }
        }
        $values[] = $id;

        $query = "UPDATE {$this->table} SET " . implode(', ', $fields) . " WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute($values);
    }

    public function delete($id) {
        $query = "DELETE FROM {$this->table} WHERE id = ?";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([$id]);
    }

    public function getUpcomingEvents($limit = 6) {
        $query = "SELECT * FROM {$this->table} 
                 WHERE date >= CURDATE() 
                 ORDER BY date ASC 
                 LIMIT ?";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute([$limit]);
        return $stmt->fetchAll();
    }
}