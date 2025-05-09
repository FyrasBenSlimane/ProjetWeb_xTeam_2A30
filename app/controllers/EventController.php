<?php
require_once __DIR__ . '/../models/Event.php';

class EventController {
    private $eventModel;
    private $db;

    public function __construct($database) {
        $this->db = $database;
        $this->eventModel = new Event($database);
    }

    public function index() {
        $events = $this->eventModel->getAll();
        require __DIR__ . '/../views/admin/events/list.php';
    }

    public function create() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->getEventData();
            
            // Handle image upload
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $data['image'] = $this->handleImageUpload();
            }

            if ($this->eventModel->create($data)) {
                $_SESSION['success_message'] = "Event created successfully!";
                header('Location: events.php');
                exit;
            }
        }
        require __DIR__ . '/../views/admin/events/create.php';
    }

    public function edit($id) {
        $event = $this->eventModel->getById($id);
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = $this->getEventData();
            
            if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
                $data['image'] = $this->handleImageUpload();
            }

            if ($this->eventModel->update($id, $data)) {
                $_SESSION['success_message'] = "Event updated successfully!";
                header('Location: events.php');
                exit;
            }
        }
        require __DIR__ . '/../views/admin/events/edit.php';
    }

    public function delete($id) {
        if ($this->eventModel->delete($id)) {
            $_SESSION['success_message'] = "Event deleted successfully!";
        }
        header('Location: events.php');
        exit;
    }

    private function getEventData() {
        return [
            'title' => $_POST['title'],
            'date' => $_POST['date'],
            'location' => $_POST['location'],
            'description' => $_POST['description']
        ];
    }

    private function handleImageUpload() {
        $target_dir = "../public/assets/images/events/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_extension = strtolower(pathinfo($_FILES["image"]["name"], PATHINFO_EXTENSION));
        $image = "event_" . time() . "." . $file_extension;
        $target_file = $target_dir . $image;
        
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            return "assets/images/events/" . $image;
        }
        return null;
    }

    public function getUpcoming() {
        return $this->eventModel->getUpcomingEvents();
    }
}