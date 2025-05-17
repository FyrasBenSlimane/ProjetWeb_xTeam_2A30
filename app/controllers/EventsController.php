<?php
/**
 * EventsController Class
 * Handles events display and registration functionality
 */
class EventsController extends Controller {
    private $eventsModel;
    private $isLoggedIn;
    private $userId;

    public function __construct() {
        $this->eventsModel = $this->model('EventsModel');
        
        // Check if user is logged in
        $this->isLoggedIn = isset($_SESSION['user_id']);
        $this->userId = $this->isLoggedIn ? $_SESSION['user_id'] : 0;
    }

    /**
     * Display all events
     */
    public function index() {
        // Get upcoming events
        $events = $this->eventsModel->getUpcomingEvents();
        
        // If user is logged in, get their registrations
        $registrations = [];
        if ($this->isLoggedIn) {
            $registrations = $this->eventsModel->getUserRegistrations($this->userId);
        }
        
        $data = [
            'title' => 'Community Events',
            'description' => 'Join events, workshops, and meetups with fellow professionals',
            'events' => $events,
            'registrations' => $registrations,
            'is_logged_in' => $this->isLoggedIn,
            'user_id' => $this->userId
        ];
        
        $this->view('community/events', $data);
    }
    
    /**
     * Display single event
     * 
     * @param int $id Event ID
     */
    public function viewEvent($id) {
        // Get event details
        $event = $this->eventsModel->getEventById($id);
        
        if (!$event) {
            flash('event_error', 'Event not found', 'alert alert-danger');
            redirect('events');
        }
        
        // Check if user is registered
        $isRegistered = false;
        if ($this->isLoggedIn) {
            $isRegistered = $this->eventsModel->isUserRegistered($id, $this->userId);
        }
        
        $data = [
            'title' => $event->title,
            'description' => substr($event->description, 0, 155),
            'event' => $event,
            'is_registered' => $isRegistered,
            'is_logged_in' => $this->isLoggedIn,
            'user_id' => $this->userId
        ];
        
        $this->view('community/event_detail', $data);
    }
    
    /**
     * Show events by category
     * 
     * @param string $category Event category
     */
    public function category($category) {
        // Get events by category
        $events = $this->eventsModel->getEventsByCategory($category);
        
        // If user is logged in, get their registrations
        $registrations = [];
        if ($this->isLoggedIn) {
            $registrations = $this->eventsModel->getUserRegistrations($this->userId);
        }
        
        $data = [
            'title' => ucfirst($category) . ' Events',
            'description' => 'Browse ' . strtolower($category) . ' events and workshops',
            'events' => $events,
            'registrations' => $registrations,
            'is_logged_in' => $this->isLoggedIn,
            'user_id' => $this->userId,
            'category' => $category
        ];
        
        $this->view('community/events', $data);
    }
    
    /**
     * Register for an event
     */
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Validate user is logged in
            if (!$this->isLoggedIn) {
                flash('event_message', 'You must be logged in to register for events', 'alert alert-danger');
                redirect('users/login');
            }
            
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Get form data
            $eventId = isset($_POST['event_id']) ? trim($_POST['event_id']) : '';
            $attendanceType = isset($_POST['attendance_type']) ? trim($_POST['attendance_type']) : 'virtual';
            $notes = isset($_POST['notes']) ? trim($_POST['notes']) : '';
            
            // Validate event ID
            if (empty($eventId)) {
                flash('event_message', 'Invalid event', 'alert alert-danger');
                redirect('events');
                return;
            }
            
            // Check if the event exists and is published
            $event = $this->eventsModel->getEventById($eventId);
            if (!$event || $event->status !== 'published') {
                flash('event_message', 'Event not available for registration', 'alert alert-danger');
                redirect('events');
                return;
            }
            
            // Check if user is already registered
            if ($this->eventsModel->isUserRegistered($eventId, $this->userId)) {
                flash('event_message', 'You are already registered for this event', 'alert alert-info');
                redirect('events');
                return;
            }
            
            // Register the user
            $registrationData = [
                'event_id' => $eventId,
                'user_id' => $this->userId,
                'attendance_type' => $attendanceType,
                'notes' => $notes
            ];
            
            if ($this->eventsModel->registerForEvent($registrationData)) {
                flash('event_message', 'You have successfully registered for the event', 'alert alert-success');
            } else {
                flash('event_message', 'There was an error registering for the event', 'alert alert-danger');
            }
            
            redirect('events');
        } else {
            // GET request, redirect to events page
            redirect('events');
        }
    }
    
    /**
     * Display events by type
     * 
     * @param string $type Event type
     */
    public function type($type) {
        // Get events by type
        $events = $this->eventsModel->getEventsByType($type);
        
        // If user is logged in, get their registrations
        $registrations = [];
        if ($this->isLoggedIn) {
            $registrations = $this->eventsModel->getUserRegistrations($this->userId);
        }
        
        $data = [
            'title' => ucfirst($type) . ' Events',
            'description' => 'Browse ' . strtolower($type) . ' events',
            'events' => $events,
            'registrations' => $registrations,
            'is_logged_in' => $this->isLoggedIn,
            'user_id' => $this->userId,
            'event_type' => $type
        ];
        
        $this->view('community/events', $data);
    }
    
    /**
     * Calendar view of events (AJAX)
     */
    public function calendar() {
        // Check if this is an AJAX request
        if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
            // Get start and end dates from request
            $startDate = isset($_GET['start']) ? $_GET['start'] : date('Y-m-01');
            $endDate = isset($_GET['end']) ? $_GET['end'] : date('Y-m-t');
            
            // Get events in the date range
            $events = $this->eventsModel->getEventsByDateRange($startDate, $endDate);
            
            // Format events for the calendar
            $calendarEvents = [];
            foreach ($events as $event) {
                $calendarEvents[] = [
                    'id' => $event->id,
                    'title' => $event->title,
                    'start' => $event->start_date,
                    'end' => $event->end_date,
                    'url' => URL_ROOT . '/events/view/' . $event->id,
                    'type' => $event->event_type,
                    'isVirtual' => (bool)$event->is_virtual
                ];
            }
            
            // Return JSON response
            header('Content-Type: application/json');
            echo json_encode($calendarEvents);
        } else {
            // Not an AJAX request, redirect to events page
            redirect('events');
        }
    }
    
    /**
     * My Registrations page
     */
    public function myregistrations() {
        // Check if user is logged in
        if (!$this->isLoggedIn) {
            flash('event_message', 'You must be logged in to view your registrations', 'alert alert-danger');
            redirect('users/login');
        }
        
        // Get user's registrations
        $registrations = $this->eventsModel->getUserRegistrations($this->userId);
        
        $data = [
            'title' => 'My Event Registrations',
            'description' => 'View and manage your event registrations',
            'registrations' => $registrations,
            'is_logged_in' => $this->isLoggedIn,
            'user_id' => $this->userId
        ];
        
        $this->view('community/my_registrations', $data);
    }
    
    /**
     * Cancel a registration
     * 
     * @param int $id Registration ID
     */
    public function cancelregistration($id = null) {
        // Check if user is logged in
        if (!$this->isLoggedIn) {
            flash('event_message', 'You must be logged in to cancel a registration', 'alert alert-danger');
            redirect('users/login');
        }
        
        // Check if ID is provided
        if (!$id) {
            flash('event_message', 'Invalid registration', 'alert alert-danger');
            redirect('events/myregistrations');
        }
        
        // Verify this registration belongs to the user
        $this->db = new Database;
        $this->db->query('SELECT user_id FROM event_registrations WHERE id = :id');
        $this->db->bind(':id', $id);
        $registration = $this->db->single();
        
        if (!$registration || $registration->user_id != $this->userId) {
            flash('event_message', 'You are not authorized to cancel this registration', 'alert alert-danger');
            redirect('events/myregistrations');
        }
        
        // Cancel registration (update status to 'canceled')
        if ($this->eventsModel->updateRegistrationStatus($id, 'rejected')) {
            flash('event_message', 'Your registration has been canceled', 'alert alert-success');
        } else {
            flash('event_message', 'There was an error canceling your registration', 'alert alert-danger');
        }
        
        redirect('events/myregistrations');
    }
} 