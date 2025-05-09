<?php
require_once __DIR__ . '/../models/Participant.php';

class ParticipantController {
    private $participantModel;
    private $db;

    public function __construct($database) {
        $this->db = $database;
        $this->participantModel = new Participant($database);
    }

    public function index() {
        $filters = [
            'event_id' => $_GET['event'] ?? null,
            'status' => $_GET['status'] ?? null
        ];
        
        $participants = $this->participantModel->getAll($filters);
        $stats = $this->participantModel->getStats();
        require __DIR__ . '/../views/admin/participants/list.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = [
                'name' => $_POST['name'],
                'email' => $_POST['email'],
                'phone' => $_POST['phone'] ?? null,
                'event_id' => $_POST['event_id'],
                'status' => 'pending'
            ];

            if ($this->participantModel->create($data)) {
                $_SESSION['success_message'] = "Registration successful!";
                // Envoyer email de confirmation ici
                header('Location: event-detail.php?id=' . $data['event_id']);
                exit;
            }
        }
    }

    public function updateStatus($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $status = $_POST['status'];
            $adminNotes = $_POST['admin_notes'] ?? null;

            if ($this->participantModel->updateStatus($id, $status, $adminNotes)) {
                $_SESSION['success_message'] = "Participant status updated successfully!";
            }
        }
        header('Location: participants.php');
        exit;
    }

    public function getRecentParticipants() {
        return $this->participantModel->getRecentParticipants();
    }

    public function getStats() {
        return $this->participantModel->getStats();
    }

    public function exportToCSV() {
        $participants = $this->participantModel->getAll();
        
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="participants.csv"');
        
        $output = fopen('php://output', 'w');
        
        // En-têtes CSV
        fputcsv($output, ['Name', 'Email', 'Phone', 'Event', 'Status', 'Registration Date']);
        
        // Données
        foreach ($participants as $participant) {
            fputcsv($output, [
                $participant['name'],
                $participant['email'],
                $participant['phone'],
                $participant['event_title'],
                $participant['status'],
                $participant['registration_date']
            ]);
        }
        
        fclose($output);
        exit;
    }
}