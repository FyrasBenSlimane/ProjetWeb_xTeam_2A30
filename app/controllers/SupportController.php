<?php
// Support Controller
// Handles all support-related functionality including tickets management
class SupportController extends Controller
{
    private $supportModel;
    private $userModel;

    public function __construct()
    {
        // Check if user is logged in for protected routes
        if (!isLoggedIn()) {
            redirect('users/login');
        }

        // Load models
        $this->supportModel = $this->model('Support');
        $this->userModel = $this->model('User');
    }    // Main support index
    public function index()
    {
        $data = [
            'title' => 'Support Center',
            'description' => 'Get help and support for your account',
            'account_type' => $_SESSION['user_account_type']
        ];

        $this->view('layouts/header', $data);
        $this->view('support/index', $data);
        $this->view('layouts/footer');
    }

    // View all user support tickets
    public function tickets()
    {
        if (!isset($_SESSION['user_id'])) {
            // User not logged in or session expired
            flash('error_message', 'Your session has expired. Please log in again.', 'alert alert-danger');
            redirect('users/login');
            return;
        }
        // Get tickets for the current user
        try {
            $tickets = $this->supportModel->getTicketsByUserId($_SESSION['user_id']);

            $data = [
                'title' => 'My Support Tickets',
                'description' => 'View and manage your support tickets',
                'tickets' => $tickets,  // This passes the tickets to the view
                'user_id' => $_SESSION['user_id']
            ];

            $this->view('layouts/header', $data);
            $this->view('support/tickets', $data);
            $this->view('layouts/footer');
        } catch (Exception $e) {
            // Log the error
            error_log('Error retrieving tickets: ' . $e->getMessage());

            // Show error to user
            flash('error_message', 'There was an error retrieving your tickets. Please try again later.', 'alert alert-danger');
            $data = [
                'title' => 'My Support Tickets',
                'description' => 'View and manage your support tickets',
                'tickets' => [],
                'error' => $e->getMessage()
            ];

            $this->view('layouts/header', $data);
            $this->view('support/tickets', $data);
            $this->view('layouts/footer');
        }
    }

    // Create a new support ticket
    public function newTicket()
    {
        // Check if request method is POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Init data
            $data = [
                'title' => 'Create New Ticket',
                'description' => 'Submit a new support request',
                'subject' => trim($_POST['subject']),
                'category' => trim($_POST['category']),
                'priority' => trim($_POST['priority']),
                'description' => trim($_POST['description']),
                'user_id' => $_SESSION['user_id'],
                'subject_err' => '',
                'description_err' => ''
            ];

            // Validate data
            if (empty($data['subject'])) {
                $data['subject_err'] = 'Please enter a subject';
            }

            if (empty($data['description'])) {
                $data['description_err'] = 'Please provide a description';
            }

            // Make sure there are no errors
            if (empty($data['subject_err']) && empty($data['description_err'])) {                // Create ticket
                if ($this->supportModel->createTicket($data)) {
                    flash('ticket_message', 'Support ticket created successfully');
                    redirect('support/tickets');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('layouts/header', $data);
                $this->view('support/new-ticket', $data);
                $this->view('layouts/footer');
            }
        } else {
            // Initial form view
            $data = [
                'title' => 'Create New Ticket',
                'description' => 'Submit a new support request',
                'subject' => '',
                'category' => '',
                'priority' => '',
                'description' => '',
                'subject_err' => '',
                'description_err' => '',
                'css' => ['components/support.css', 'components/support-form.css']
            ];

            $this->view('layouts/header', $data);
            $this->view('support/new-ticket', $data);
            $this->view('layouts/footer');
        }
    }

    // View ticket details
    public function viewTicket($id = null)
    {
        if ($id === null) {
            redirect('support/tickets');
        }

        // Get ticket details
        $ticket = $this->supportModel->getTicketById($id);

        // Make sure ticket exists and belongs to current user
        if ($ticket && $ticket->user_id == $_SESSION['user_id']) {
            // Get ticket responses
            $responses = $this->supportModel->getTicketResponses($id);
            $data = [
                'title' => 'Ticket #' . $id,
                'description' => 'Ticket details',
                'ticket' => $ticket,
                'responses' => $responses,
                'css' => ['components/support.css'] // Include our new support CSS file
            ];

            $this->view('layouts/header', $data);
            $this->view('support/view-ticket', $data);
            $this->view('layouts/footer');
        } else {
            // Ticket not found or not authorized
            flash('ticket_message', 'Ticket not found or you do not have permission to view it', 'alert alert-danger');
            redirect('support/tickets');
        }
    }

    // Add response to ticket
    public function addResponse()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $ticketId = $_POST['ticket_id'] ?? null;
            $response = $_POST['response'] ?? '';

            if ($ticketId && !empty($response)) {
                // Get the ticket
                $ticket = $this->supportModel->getTicketById($ticketId);
                $isAdmin = ($_SESSION['user_account_type'] === 'admin');

                // Admin can respond to any ticket
                if ($isAdmin) {
                    // Add response to ticket as admin
                    if ($this->supportModel->addTicketResponse($ticketId, $response, $_SESSION['user_id'], true)) {
                        // Update ticket status to awaiting-response when admin responds
                        $this->supportModel->updateTicketStatus($ticketId, 'awaiting-response');
                        flash('ticket_message', 'Response added successfully');
                        redirect('support/viewTicket/' . $ticketId);
                    } else {
                        flash('ticket_message', 'Failed to add response', 'alert alert-danger');
                        redirect('support/viewTicket/' . $ticketId);
                    }
                }
                // Regular user can only respond if ticket belongs to them AND an admin has already responded
                else if ($ticket && $ticket->user_id == $_SESSION['user_id']) {
                    // Check if an admin has responded first
                    $hasAdminResponses = $this->supportModel->hasAdminResponses($ticketId);

                    if ($hasAdminResponses) {
                        // Add response to ticket
                        if ($this->supportModel->addTicketResponse($ticketId, $response, $_SESSION['user_id'], false)) {
                            // Update ticket status if needed
                            $this->supportModel->updateTicketStatus($ticketId, 'awaiting-response');
                            flash('ticket_message', 'Response added successfully');
                            redirect('support/viewTicket/' . $ticketId);
                        } else {
                            flash('ticket_message', 'Failed to add response', 'alert alert-danger');
                            redirect('support/viewTicket/' . $ticketId);
                        }
                    } else {
                        flash('ticket_message', 'Please wait for an admin to respond first before adding your response', 'alert alert-warning');
                        redirect('support/viewTicket/' . $ticketId);
                    }
                } else {
                    flash('ticket_message', 'Unauthorized access', 'alert alert-danger');
                    redirect('support/tickets');
                }
            } else {
                flash('ticket_message', 'Invalid request', 'alert alert-danger');
                redirect('support/tickets');
            }
        } else {
            redirect('support/tickets');
        }
    }

    // Add reply to ticket    
    public function addReply()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $ticketId = $_POST['ticket_id'] ?? null;
            $message = $_POST['message'] ?? '';

            if ($ticketId && !empty($message)) {
                // Get the ticket
                $ticket = $this->supportModel->getTicketById($ticketId);
                $isAdmin = ($_SESSION['user_account_type'] === 'admin');
                $isTicketCreator = ($ticket && $ticket->user_id == $_SESSION['user_id']);

                // Admin can reply to any ticket
                if ($isAdmin) {
                    // Add reply to ticket as admin
                    if ($this->supportModel->addTicketResponse($ticketId, $message, $_SESSION['user_id'], true)) {
                        // Update ticket status to awaiting-response when admin replies
                        $this->supportModel->updateTicketStatus($ticketId, 'awaiting-response');
                        flash('ticket_message', 'Reply added successfully');
                        redirect('support/viewTicket/' . $ticketId);
                    } else {
                        flash('ticket_message', 'Failed to add reply', 'alert alert-danger');
                        redirect('support/viewTicket/' . $ticketId);
                    }
                }
                // Regular user can only reply if there has been an admin response first
                else {
                    // Check if an admin has responded first
                    $hasAdminResponses = $this->supportModel->hasAdminResponses($ticketId);

                    if ($hasAdminResponses) {
                        // Check if user is ticket creator or has permission to reply
                        if ($isTicketCreator) {
                            // Add reply to ticket
                            if ($this->supportModel->addTicketResponse($ticketId, $message, $_SESSION['user_id'], false)) {
                                // Update ticket status
                                $this->supportModel->updateTicketStatus($ticketId, 'awaiting-response');
                                flash('ticket_message', 'Reply added successfully');
                                redirect('support/viewTicket/' . $ticketId);
                            } else {
                                flash('ticket_message', 'Failed to add reply', 'alert alert-danger');
                                redirect('support/viewTicket/' . $ticketId);
                            }
                        } else {
                            flash('ticket_message', 'You do not have permission to reply to this ticket', 'alert alert-danger');
                            redirect('support/tickets');
                        }
                    } else {
                        flash('ticket_message', 'Please wait for an admin to respond first before adding your reply', 'alert alert-warning');
                        redirect('support/viewTicket/' . $ticketId);
                    }
                }
            } else {
                flash('ticket_message', 'Invalid request', 'alert alert-danger');
                redirect('support/tickets');
            }
        } else {
            redirect('support/tickets');
        }
    }

    // Close ticket
    public function closeTicket($id = null)
    {
        if ($id === null) {
            redirect('support/tickets');
        }

        // Only admins can close tickets
        $isAdmin = ($_SESSION['user_account_type'] === 'admin');
        if (!$isAdmin) {
            flash('ticket_message', 'Only admins can close tickets', 'alert alert-danger');
            redirect('support/tickets');
            return;
        }

        $ticket = $this->supportModel->getTicketById($id);

        if ($ticket) {
            if ($this->supportModel->updateTicketStatus($id, 'closed')) {
                flash('ticket_message', 'Ticket closed successfully');
            } else {
                flash('ticket_message', 'Failed to close ticket', 'alert alert-danger');
            }
        } else {
            flash('ticket_message', 'Ticket not found', 'alert alert-danger');
        }

        redirect('support/tickets');
    }

    // Delete ticket
    public function deleteTicket($id = null)
    {
        if ($id === null) {
            redirect('support/tickets');
        }

        // Verify ticket belongs to user
        $ticket = $this->supportModel->getTicketById($id);

        if ($ticket && $ticket->user_id == $_SESSION['user_id']) {
            if ($this->supportModel->deleteTicket($id)) {
                flash('ticket_message', 'Ticket deleted successfully');
            } else {
                flash('ticket_message', 'Failed to delete ticket', 'alert alert-danger');
            }
        } else {
            flash('ticket_message', 'Unauthorized access', 'alert alert-danger');
        }

        redirect('support/tickets');
    }

    // Edit ticket form
    public function editTicket($id = null)
    {
        if ($id === null) {
            redirect('support/tickets');
        }

        // Check if form is submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form submission
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Init data
            $data = [
                'id' => $id,
                'title' => 'Edit Ticket',
                'description' => 'Update your support request',
                'subject' => trim($_POST['subject']),
                'category' => trim($_POST['category']),
                'priority' => trim($_POST['priority']),
                'description' => trim($_POST['description']),
                'user_id' => $_SESSION['user_id'],
                'subject_err' => '',
                'description_err' => ''
            ];

            // Validate data
            if (empty($data['subject'])) {
                $data['subject_err'] = 'Please enter a subject';
            }

            if (empty($data['description'])) {
                $data['description_err'] = 'Please provide a description';
            }

            // Make sure there are no errors
            if (empty($data['subject_err']) && empty($data['description_err'])) {
                // Update ticket
                if ($this->supportModel->updateTicket($data)) {
                    flash('ticket_message', 'Ticket updated successfully');
                    redirect('support/viewTicket/' . $id);
                } else {
                    flash('ticket_message', 'Failed to update ticket', 'alert alert-danger');
                    $this->view('layouts/header', $data);
                    $this->view('support/edit-ticket', $data);
                    $this->view('layouts/footer');
                }
            } else {
                // Load view with errors
                $this->view('layouts/header', $data);
                $this->view('support/edit-ticket', $data);
                $this->view('layouts/footer');
            }
        } else {
            // Get ticket details
            $ticket = $this->supportModel->getTicketById($id);

            // Verify ticket belongs to user
            if ($ticket && $ticket->user_id == $_SESSION['user_id']) {
                $data = [
                    'id' => $id,
                    'title' => 'Edit Ticket #' . $id,
                    'description' => 'Update your support request',
                    'subject' => $ticket->subject,
                    'category' => $ticket->category,
                    'priority' => $ticket->priority,
                    'description' => $ticket->description,
                    'subject_err' => '',
                    'description_err' => ''
                ];

                $this->view('layouts/header', $data);
                $this->view('support/edit-ticket', $data);
                $this->view('layouts/footer');
            } else {
                flash('ticket_message', 'Unauthorized access', 'alert alert-danger');
                redirect('support/tickets');
            }
        }
    }
    // Reopen ticket
    public function reopenTicket($id = null)
    {
        if ($id === null) {
            redirect('support/tickets');
        }

        // Only admins can reopen tickets
        $isAdmin = ($_SESSION['user_account_type'] === 'admin');
        if (!$isAdmin) {
            flash('ticket_message', 'Only admins can reopen tickets', 'alert alert-danger');
            redirect('support/tickets');
            return;
        }

        $ticket = $this->supportModel->getTicketById($id);

        if ($ticket) {
            if ($this->supportModel->updateTicketStatus($id, 'open')) {
                flash('ticket_message', 'Ticket reopened successfully');
            } else {
                flash('ticket_message', 'Failed to reopen ticket', 'alert alert-danger');
            }
        } else {
            flash('ticket_message', 'Ticket not found', 'alert alert-danger');
        }

        redirect('support/tickets');
    }

    // FAQ page
    public function faq()
    {
        $data = [
            'title' => 'Frequently Asked Questions',
            'description' => 'Find answers to common questions'
        ];

        $this->view('layouts/header', $data);
        $this->view('support/faq', $data);
        $this->view('layouts/footer');
    }

    // Contact support page
    public function contact()
    {
        $data = [
            'title' => 'Contact Support',
            'description' => 'Get in touch with our support team'
        ];

        $this->view('layouts/header', $data);
        $this->view('support/contact', $data);
        $this->view('layouts/footer');
    }

    // Add method to handle contact form submissions
    public function submitContact()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'name' => trim($_POST['name'] ?? ''),
                'email' => trim($_POST['email'] ?? ''),
                'subject' => trim($_POST['subject'] ?? ''),
                'message' => trim($_POST['message'] ?? ''),
                'user_id' => $_SESSION['user_id'] ?? null,
                'name_err' => '',
                'email_err' => '',
                'subject_err' => '',
                'message_err' => ''
            ];

            // Validate data
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter your name';
            }

            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter your email';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Please enter a valid email';
            }

            if (empty($data['subject'])) {
                $data['subject_err'] = 'Please enter a subject';
            }

            if (empty($data['message'])) {
                $data['message_err'] = 'Please enter a message';
            }

            // Make sure there are no errors
            if (empty($data['name_err']) && empty($data['email_err']) && empty($data['subject_err']) && empty($data['message_err'])) {
                // Create a ticket from contact form
                $ticketData = [
                    'subject' => $data['subject'],
                    'description' => "Contact form submission from: {$data['name']} ({$data['email']})\n\n{$data['message']}",
                    'category' => 'contact',
                    'priority' => 'medium',
                    'user_id' => $data['user_id'] ?? 0 // Use 0 for guest submissions
                ];

                if ($this->supportModel->createTicket($ticketData)) {
                    flash('contact_message', 'Your message has been sent successfully! We\'ll get back to you shortly.');
                    redirect('support/contact');
                } else {
                    flash('contact_message', 'Something went wrong, please try again', 'alert alert-danger');
                    redirect('support/contact');
                }
            } else {
                // Load view with errors
                $this->view('layouts/header', $data);
                $this->view('support/contact', $data);
                $this->view('layouts/footer');
            }
        } else {
            redirect('support/contact');
        }
    }
    // API endpoint for submitting ticket via AJAX
    public function submitTicketAjax()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process AJAX request
            header('Content-Type: application/json');

            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'subject' => trim($_POST['subject'] ?? ''),
                'category' => trim($_POST['category'] ?? ''),
                'priority' => trim($_POST['priority'] ?? ''),
                'description' => trim($_POST['description'] ?? ''),
                'user_id' => $_SESSION['user_id'],
                'errors' => []
            ];

            // Validate data
            if (empty($data['subject'])) {
                $data['errors']['subject'] = 'Please enter a subject';
            }

            if (empty($data['description'])) {
                $data['errors']['description'] = 'Please provide a description';
            }

            if (empty($data['errors'])) {
                // Create ticket
                $ticketId = $this->supportModel->createTicket($data);

                if ($ticketId) {
                    // Handle file uploads if present
                    $fileUploadSuccess = true;
                    $fileErrors = [];
                    if (isset($_FILES['attachments']) && !empty($_FILES['attachments']['name'][0])) {
                        // Process file uploads
                        $maxSize = 5 * 1024 * 1024; // 5MB
                        $allowedTypes = [
                            'image/jpeg',
                            'image/jpg',
                            'image/png',
                            'application/pdf',
                            'application/msword',
                            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                            'text/plain'
                        ];
                        $uploadDir = APPROOT . '/../public/uploads/support_attachments/';

                        // Create directory if it doesn't exist
                        if (!file_exists($uploadDir)) {
                            mkdir($uploadDir, 0755, true);
                        }

                        $files = $_FILES['attachments'];
                        for ($i = 0; $i < count($files['name']); $i++) {
                            if ($files['error'][$i] === 0) {
                                // Validate file size
                                if ($files['size'][$i] > $maxSize) {
                                    $fileUploadSuccess = false;
                                    $fileErrors[] = "File {$files['name'][$i]} exceeds maximum file size (5MB)";
                                    continue;
                                }

                                // Validate file type
                                $fileType = $files['type'][$i];
                                if (!in_array($fileType, $allowedTypes)) {
                                    $fileUploadSuccess = false;
                                    $fileErrors[] = "File {$files['name'][$i]} has invalid file type";
                                    continue;
                                }

                                // Generate safe filename
                                $fileName = $ticketId . '_' . time() . '_' . bin2hex(random_bytes(8)) . '_' . basename($files['name'][$i]);
                                $targetFile = $uploadDir . $fileName;

                                // Upload file
                                if (move_uploaded_file($files['tmp_name'][$i], $targetFile)) {
                                    // File uploaded successfully - could insert into database if needed
                                } else {
                                    $fileUploadSuccess = false;
                                    $fileErrors[] = "Failed to upload file {$files['name'][$i]}";
                                }
                            } else {
                                $fileUploadSuccess = false;
                                $fileErrors[] = "Error uploading file {$files['name'][$i]}: " . $files['error'][$i];
                            }
                        }
                    }

                    if ($fileUploadSuccess) {
                        echo json_encode([
                            'success' => true,
                            'message' => 'Support ticket created successfully',
                            'ticket_id' => $ticketId,
                            'redirect' => URL_ROOT . '/support/tickets'
                        ]);
                    } else {
                        echo json_encode([
                            'success' => true,
                            'message' => 'Support ticket created, but there were issues with file uploads',
                            'ticket_id' => $ticketId,
                            'file_errors' => $fileErrors,
                            'redirect' => URL_ROOT . '/support/tickets'
                        ]);
                    }
                } else {
                    echo json_encode([
                        'success' => false,
                        'message' => 'Failed to create ticket',
                        'errors' => ['system' => 'Database error']
                    ]);
                }
            } else {
                echo json_encode([
                    'success' => false,
                    'message' => 'Validation errors',
                    'errors' => $data['errors']
                ]);
            }
            return;
        } else {
            // Handle non-POST requests with JSON response
            header('Content-Type: application/json');
            echo json_encode([
                'success' => false,
                'message' => 'Invalid request method'
            ]);
        }
    }
}
