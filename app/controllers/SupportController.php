<?php
class SupportController extends Controller
{
    private $userModel;
    private $supportModel;
    private $contactModel; // Add Contact model
    private $fileUploadHelper; // Add file upload helper property

    public function __construct()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            redirect('users/auth?action=login');
        }

        $this->userModel = $this->model('User');
        $this->supportModel = $this->model('Support');
        $this->contactModel = $this->model('Contact'); // Initialize Contact model
        $this->fileUploadHelper = $this->helper('FileUpload', 'public/uploads/support_attachments/'); // Instantiate helper with specific path
    }

    /**
     * Support tickets dashboard
     */
    public function index()
    {
        // Get all tickets for the current user
        $tickets = $this->supportModel->getTicketsByUser($_SESSION['user_id']);

        // Get ticket counts by status for stats
        $openCount = 0;
        $pendingCount = 0;
        $closedCount = 0;
        $answeredCount = 0;

        foreach ($tickets as $ticket) {
            switch ($ticket->status) {
                case 'open':
                    $openCount++;
                    break;
                case 'pending':
                    $pendingCount++;
                    break;
                case 'answered':
                    $answeredCount++;
                    break;
                case 'closed':
                    $closedCount++;
                    break;
            }
        }

        $data = [
            'title' => 'Support Center',
            'description' => 'View and manage your support tickets',
            'tickets' => $tickets,
            'stats' => [
                'total' => count($tickets),
                'open' => $openCount,
                'pending' => $pendingCount + $answeredCount,
                'closed' => $closedCount
            ]
        ];

        $this->view('users/support/index', $data);
    }

    /**
     * View a specific ticket
     */
    public function viewTicket($id = null)
    {
        // Check if ID is set
        if (!$id) {
            flash('ticket_message', 'Invalid ticket ID', 'alert alert-danger');
            redirect('support');
        }

        // Get ticket details
        $ticket = $this->supportModel->getTicketById($id);

        // Verify ticket exists and belongs to this user
        if (!$ticket || $ticket->user_id != $_SESSION['user_id']) {
            flash('ticket_message', 'You do not have permission to view this ticket', 'alert alert-danger');
            redirect('support');
        }

        // Get ticket replies
        $replies = $this->supportModel->getTicketReplies($id);

        $data = [
            'title' => 'Ticket: ' . $ticket->subject,
            'description' => 'View ticket details',
            'ticket' => $ticket,
            'replies' => $replies
        ];

        $this->view('users/support/view', $data);
    }

    /**
     * Create a new support ticket
     */
    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            // Sanitize POST data (excluding file input)
            // Replace deprecated FILTER_SANITIZE_STRING with htmlspecialchars
            $postData = [];
            foreach ($_POST as $key => $value) {
                $postData[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            }

            // Init data
            $data = [
                'title' => 'Create Support Ticket',
                'description' => 'Submit a new support ticket',
                'subject' => trim($postData['subject'] ?? ''),
                'description' => trim($postData['description'] ?? ''),
                'category' => trim($postData['category'] ?? ''),
                'priority' => trim($postData['priority'] ?? 'medium'),
                'is_draft' => isset($_POST['save_draft']), // Check if the save as draft button was clicked
                'attachment' => $_FILES['attachment'] ?? null, // Get file data
                'attachment_filename' => null, // Initialize attachment filename
                'subject_err' => '',
                'description_err' => '',
                'category_err' => '',
                'priority_err' => '',
                'attachment_err' => '' // Add attachment error
            ];

            // Handle file upload
            $uploadedFileName = null;
            if (!empty($data['attachment']) && $data['attachment']['error'] == UPLOAD_ERR_OK) {
                // Validate file type and size
                $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf', 'application/msword', 
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 
                                'application/zip', 'application/x-zip-compressed'];
                $maxSize = 10 * 1024 * 1024; // 10MB
                
                if (!in_array($data['attachment']['type'], $allowedTypes)) {
                    $data['attachment_err'] = 'Invalid file type. Allowed types: JPG, PNG, PDF, DOC, DOCX, ZIP';
                } elseif ($data['attachment']['size'] > $maxSize) {
                    $data['attachment_err'] = 'File size exceeds the maximum limit (10MB)';
                } else {
                    $uploadedFileName = $this->fileUploadHelper->upload($data['attachment'], 'ticket_' . $_SESSION['user_id']);
                    if (!$uploadedFileName) {
                        $data['attachment_err'] = $this->fileUploadHelper->getError();
                    } else {
                        $data['attachment_filename'] = $uploadedFileName;
                    }
                }
            } elseif (!empty($data['attachment']) && $data['attachment']['error'] != UPLOAD_ERR_NO_FILE) {
                // Handle other upload errors with more specific messages
                switch ($data['attachment']['error']) {
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        $data['attachment_err'] = 'The uploaded file exceeds the maximum file size limit.';
                        break;
                    case UPLOAD_ERR_PARTIAL:
                        $data['attachment_err'] = 'The file was only partially uploaded.';
                        break;
                    case UPLOAD_ERR_NO_TMP_DIR:
                        $data['attachment_err'] = 'Missing a temporary folder for file upload.';
                        break;
                    case UPLOAD_ERR_CANT_WRITE:
                        $data['attachment_err'] = 'Failed to write file to disk.';
                        break;
                    case UPLOAD_ERR_EXTENSION:
                        $data['attachment_err'] = 'A PHP extension stopped the file upload.';
                        break;
                    default:
                        $data['attachment_err'] = 'There was an error uploading the file.';
                }
            }

            // For drafts, we can be more lenient with validation
            if (!$data['is_draft']) {
                // Validate data for final submissions
                if (empty($data['subject'])) {
                    $data['subject_err'] = 'Please enter a subject for your ticket';
                }

                if (empty($data['description'])) {
                    $data['description_err'] = 'Please provide a description of your issue';
                }

                if (empty($data['category'])) {
                    $data['category_err'] = 'Please select a category';
                }
            } else {
                // For drafts, just ensure we have at least some information
                if (empty($data['subject']) && empty($data['description']) && empty($data['category']) && empty($data['attachment_filename'])) {
                    $data['subject_err'] = 'Please enter at least some information or attach a file to save as a draft';
                }
            }

            // Make sure errors are empty (including attachment error for non-drafts)
            if (empty($data['subject_err']) && empty($data['description_err']) && empty($data['category_err']) && (empty($data['attachment_err']) || $data['is_draft'])) {
                // Submit ticket
                $ticketData = [
                    'user_id' => $_SESSION['user_id'],
                    'subject' => $data['subject'],
                    'description' => $data['description'],
                    'category' => $data['category'],
                    'priority' => $data['priority'],
                    'is_draft' => $data['is_draft'],
                    'attachment_filename' => $data['attachment_filename'] // Pass filename to model
                ];

                $result = $this->supportModel->createTicket($ticketData);
                if ($result) {
                    if ($data['is_draft']) {
                        flash('ticket_message', 'Your support ticket has been saved as a draft');
                        redirect('support/drafts'); // Redirect to drafts page
                    } else {
                        flash('ticket_message', 'Your support ticket has been submitted');
                        redirect('support');
                    }
                } else {
                    // If DB insert fails, delete the uploaded file if any
                    if ($uploadedFileName) {
                        $this->fileUploadHelper->delete($uploadedFileName);
                    }
                    flash('ticket_message', 'Something went wrong creating the ticket', 'alert alert-danger');
                    $this->view('users/support/create', $data);
                }
            } else {
                // If validation fails, delete the uploaded file if any
                if ($uploadedFileName && !$data['is_draft']) { // Only delete if not saving as draft
                    $this->fileUploadHelper->delete($uploadedFileName);
                }
                // Load view with errors
                $this->view('users/support/create', $data);
            }
        } else {
            // Initial GET request
            $data = [
                'title' => 'Create Support Ticket',
                'description' => 'Submit a new support ticket',
                'subject' => '',
                'description' => '',
                'category' => '',
                'priority' => 'medium',
                'is_draft' => false,
                'attachment_filename' => null,
                'subject_err' => '',
                'description_err' => '',
                'category_err' => '',
                'priority_err' => '',
                'attachment_err' => ''
            ];

            $this->view('users/support/create', $data);
        }
    }

    /**
     * Reply to a support ticket
     */
    public function reply($id = null)
    {
        if (!$id) {
            flash('ticket_message', 'Invalid ticket ID', 'alert alert-danger');
            redirect('support');
        }

        // Get ticket details
        $ticket = $this->supportModel->getTicketById($id);

        // Verify ticket exists and belongs to this user
        if (!$ticket || $ticket->user_id != $_SESSION['user_id']) {
            flash('ticket_message', 'You do not have permission to reply to this ticket', 'alert alert-danger');
            redirect('support');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form - replace deprecated FILTER_SANITIZE_STRING with htmlspecialchars
            $postData = [];
            foreach ($_POST as $key => $value) {
                $postData[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            }

            // Init data
            $data = [
                'title' => 'Reply to Ticket',
                'ticket_id' => $id,
                'message' => trim($postData['message']),
                'message_err' => '',
                'ticket' => $ticket
            ];

            // Validate message
            if (empty($data['message'])) {
                $data['message_err'] = 'Please enter a message';
            }

            // Make sure errors are empty
            if (empty($data['message_err'])) {
                // Submit reply
                $replyData = [
                    'ticket_id' => $id,
                    'user_id' => $_SESSION['user_id'],
                    'message' => $data['message'],
                    'is_admin' => 0 // User reply, not admin
                ];

                if ($this->supportModel->addReply($replyData)) {
                    // Update ticket status to 'open' when user replies
                    $this->supportModel->updateTicketStatus($id, 'open');
                    flash('ticket_message', 'Your reply has been submitted');
                    redirect('support/viewTicket/' . $id);
                } else {
                    flash('ticket_message', 'Something went wrong', 'alert alert-danger');
                    $this->view('users/support/reply', $data);
                }
            } else {
                // Load view with errors
                $this->view('users/support/reply', $data);
            }
        } else {
            $data = [
                'title' => 'Reply to Ticket',
                'ticket_id' => $id,
                'message' => '',
                'message_err' => '',
                'ticket' => $ticket
            ];

            $this->view('users/support/reply', $data);
        }
    }

    /**
     * Close a ticket
     */
    public function close($id = null)
    {
        if (!$id) {
            flash('ticket_message', 'Invalid ticket ID', 'alert alert-danger');
            redirect('support');
        }

        // Get ticket details
        $ticket = $this->supportModel->getTicketById($id);

        // Verify ticket exists and belongs to this user
        if (!$ticket || $ticket->user_id != $_SESSION['user_id']) {
            flash('ticket_message', 'You do not have permission to close this ticket', 'alert alert-danger');
            redirect('support');
        }

        if ($this->supportModel->updateTicketStatus($id, 'closed')) {
            flash('ticket_message', 'Your ticket has been closed');
        } else {
            flash('ticket_message', 'Something went wrong', 'alert alert-danger');
        }

        redirect('support/viewTicket/' . $id);
    }

    /**
     * Reopen a closed ticket
     */
    public function reopen($id = null)
    {
        if (!$id) {
            flash('ticket_message', 'Invalid ticket ID', 'alert alert-danger');
            redirect('support');
        }

        // Get ticket details
        $ticket = $this->supportModel->getTicketById($id);

        // Verify ticket exists and belongs to this user
        if (!$ticket || $ticket->user_id != $_SESSION['user_id']) {
            flash('ticket_message', 'You do not have permission to reopen this ticket', 'alert alert-danger');
            redirect('support');
        }

        if ($this->supportModel->updateTicketStatus($id, 'open')) {
            flash('ticket_message', 'Your ticket has been reopened');
        } else {
            flash('ticket_message', 'Something went wrong', 'alert alert-danger');
        }

        redirect('support/viewTicket/' . $id);
    }

    /**
     * Delete a ticket
     */
    public function delete($id = null)
    {
        if (!$id) {
            flash('ticket_message', 'Invalid ticket ID', 'alert alert-danger');
            redirect('support');
        }

        // Get ticket details
        $ticket = $this->supportModel->getTicketById($id);

        // Verify ticket exists and belongs to this user
        if (!$ticket || $ticket->user_id != $_SESSION['user_id']) {
            flash('ticket_message', 'You do not have permission to delete this ticket', 'alert alert-danger');
            redirect('support');
        }

        if ($this->supportModel->deleteTicket($id)) {
            flash('ticket_message', 'Your ticket has been deleted successfully');
            redirect('support');
        } else {
            flash('ticket_message', 'Something went wrong', 'alert alert-danger');
            redirect('support/viewTicket/' . $id);
        }
    }

    /**
     * Edit a ticket
     */
    public function edit($id = null)
    {
        if (!$id) {
            flash('ticket_message', 'Invalid ticket ID', 'alert alert-danger');
            redirect('support');
        }

        // Get ticket details
        $ticket = $this->supportModel->getTicketById($id);

        // Verify ticket exists and belongs to this user
        if (!$ticket || $ticket->user_id != $_SESSION['user_id']) {
            flash('ticket_message', 'You do not have permission to edit this ticket', 'alert alert-danger');
            redirect('support');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form - replace deprecated FILTER_SANITIZE_STRING with htmlspecialchars
            $postData = [];
            foreach ($_POST as $key => $value) {
                $postData[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            }

            // Init data
            $data = [
                'title' => 'Edit Support Ticket',
                'description' => 'Edit your support ticket',
                'id' => $id,
                'subject' => trim($postData['subject']),
                'description' => trim($postData['description']),
                'category' => trim($postData['category']),
                'priority' => trim($postData['priority']),
                'subject_err' => '',
                'description_err' => '',
                'category_err' => '',
                'priority_err' => '',
                'ticket' => $ticket
            ];

            // Validate data
            if (empty($data['subject'])) {
                $data['subject_err'] = 'Please enter a subject for your ticket';
            }

            if (empty($data['description'])) {
                $data['description_err'] = 'Please provide a description of your issue';
            }

            if (empty($data['category'])) {
                $data['category_err'] = 'Please select a category';
            }

            // Make sure errors are empty
            if (empty($data['subject_err']) && empty($data['description_err']) && empty($data['category_err'])) {
                // Update ticket
                $ticketData = [
                    'id' => $id,
                    'subject' => $data['subject'],
                    'description' => $data['description'],
                    'category' => $data['category'],
                    'priority' => $data['priority']
                ];

                if ($this->supportModel->updateTicket($ticketData)) {
                    flash('ticket_message', 'Your support ticket has been updated successfully');
                    redirect('support/viewTicket/' . $id);
                } else {
                    flash('ticket_message', 'Something went wrong', 'alert alert-danger');
                    $this->view('users/support/edit', $data);
                }
            } else {
                // Load view with errors
                $this->view('users/support/edit', $data);
            }
        } else {
            $data = [
                'title' => 'Edit Support Ticket',
                'description' => 'Edit your support ticket',
                'id' => $id,
                'subject' => $ticket->subject,
                'description' => $ticket->description,
                'category' => $ticket->category,
                'priority' => $ticket->priority,
                'subject_err' => '',
                'description_err' => '',
                'category_err' => '',
                'priority_err' => '',
                'ticket' => $ticket
            ];

            $this->view('users/support/edit', $data);
        }
    }

    /**
     * List draft tickets
     */
    public function drafts()
    {
        // Get all draft tickets for the current user
        $drafts = $this->supportModel->getDraftTicketsByUser($_SESSION['user_id']);

        $data = [
            'title' => 'Draft Support Tickets',
            'description' => 'View and manage your draft support tickets',
            'drafts' => $drafts
        ];

        $this->view('users/support/drafts', $data);
    }

    /**
     * Submit a draft ticket
     */
    public function submitDraft($id = null)
    {
        if (!$id) {
            flash('ticket_message', 'Invalid ticket ID', 'alert alert-danger');
            redirect('support/drafts');
        }

        // Get ticket details
        $ticket = $this->supportModel->getTicketById($id);

        // Verify ticket exists, belongs to this user, and is a draft
        if (!$ticket || $ticket->user_id != $_SESSION['user_id'] || $ticket->status != 'draft') {
            flash('ticket_message', 'You do not have permission to submit this draft', 'alert alert-danger');
            redirect('support/drafts');
        }

        // Validate that the ticket has the required fields before submitting
        if (empty($ticket->subject) || empty($ticket->description) || empty($ticket->category)) {
            flash('ticket_message', 'Please complete all required fields before submitting', 'alert alert-danger');
            redirect('support/editDraft/' . $id);
        }

        if ($this->supportModel->submitDraftTicket($id)) {
            flash('ticket_message', 'Your draft ticket has been submitted successfully');
            redirect('support');
        } else {
            flash('ticket_message', 'Something went wrong', 'alert alert-danger');
            redirect('support/drafts');
        }
    }

    /**
     * Edit a draft ticket
     */
    public function editDraft($id = null)
    {
        if (!$id) {
            flash('ticket_message', 'Invalid draft ID', 'alert alert-danger');
            redirect('support/drafts');
        }

        $draft = $this->supportModel->getTicketById($id);

        // Verify draft exists, belongs to user, and is actually a draft
        if (!$draft || $draft->user_id != $_SESSION['user_id'] || $draft->status !== 'draft') {
            flash('ticket_message', 'Draft not found or you do not have permission to edit it', 'alert alert-danger');
            redirect('support/drafts');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Replace deprecated FILTER_SANITIZE_STRING with htmlspecialchars
            $postData = [];
            foreach ($_POST as $key => $value) {
                $postData[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            }

            $data = [
                'title' => 'Edit Draft Ticket',
                'description' => 'Update your draft support ticket',
                'id' => $id,
                'subject' => trim($postData['subject'] ?? ''),
                'description' => trim($postData['description'] ?? ''),
                'category' => trim($postData['category'] ?? ''),
                'priority' => trim($postData['priority'] ?? 'medium'),
                'is_draft' => isset($_POST['save_draft']), // Check if saving again or submitting
                'attachment' => $_FILES['attachment'] ?? null,
                'existing_attachment' => $draft->attachment_filename, // Keep track of existing file
                'remove_attachment' => isset($_POST['remove_attachment']),
                'attachment_filename' => $draft->attachment_filename, // Start with existing
                'subject_err' => '',
                'description_err' => '',
                'category_err' => '',
                'priority_err' => '',
                'attachment_err' => ''
            ];

            $uploadedFileName = null;
            $deletedExisting = false;

            // Handle attachment removal
            if ($data['remove_attachment'] && $data['existing_attachment']) {
                if ($this->fileUploadHelper->delete($data['existing_attachment'])) {
                    $data['attachment_filename'] = null;
                    $data['existing_attachment'] = null; // Clear existing attachment field
                    $deletedExisting = true;
                } else {
                    $data['attachment_err'] = 'Could not remove existing attachment.';
                }
            }

            // Handle new file upload only if no error during removal
            if (empty($data['attachment_err']) && $data['attachment'] && $data['attachment']['error'] == UPLOAD_ERR_OK) {
                // Validate file type and size
                $allowedTypes = ['image/jpeg', 'image/png', 'application/pdf', 'application/msword', 
                                'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 
                                'application/zip', 'application/x-zip-compressed'];
                $maxSize = 10 * 1024 * 1024; // 10MB
                
                if (!in_array($data['attachment']['type'], $allowedTypes)) {
                    $data['attachment_err'] = 'Invalid file type. Allowed types: JPG, PNG, PDF, DOC, DOCX, ZIP';
                } elseif ($data['attachment']['size'] > $maxSize) {
                    $data['attachment_err'] = 'File size exceeds the maximum limit (10MB)';
                } else {
                    // If there's an existing file and it wasn't just removed, delete it first
                    if ($data['existing_attachment'] && !$deletedExisting) {
                        $this->fileUploadHelper->delete($data['existing_attachment']);
                    }
                    $uploadedFileName = $this->fileUploadHelper->upload($data['attachment'], 'ticket_' . $_SESSION['user_id']);
                    if (!$uploadedFileName) {
                        $data['attachment_err'] = $this->fileUploadHelper->getError();
                    } else {
                        $data['attachment_filename'] = $uploadedFileName;
                    }
                }
            } elseif ($data['attachment'] && $data['attachment']['error'] != UPLOAD_ERR_NO_FILE) {
                // Handle other upload errors with more specific messages
                switch ($data['attachment']['error']) {
                    case UPLOAD_ERR_INI_SIZE:
                    case UPLOAD_ERR_FORM_SIZE:
                        $data['attachment_err'] = 'The uploaded file exceeds the maximum file size limit.';
                        break;
                    case UPLOAD_ERR_PARTIAL:
                        $data['attachment_err'] = 'The file was only partially uploaded.';
                        break;
                    case UPLOAD_ERR_NO_TMP_DIR:
                        $data['attachment_err'] = 'Missing a temporary folder for file upload.';
                        break;
                    case UPLOAD_ERR_CANT_WRITE:
                        $data['attachment_err'] = 'Failed to write file to disk.';
                        break;
                    case UPLOAD_ERR_EXTENSION:
                        $data['attachment_err'] = 'A PHP extension stopped the file upload.';
                        break;
                    default:
                        $data['attachment_err'] = 'There was an error uploading the file.';
                }
            }

            // Validation (similar to create, depends on whether submitting or saving draft)
            if (!$data['is_draft']) {
                // Validate for final submission
                if (empty($data['subject'])) $data['subject_err'] = 'Please enter a subject.';
                if (empty($data['description'])) $data['description_err'] = 'Please provide a description.';
                if (empty($data['category'])) $data['category_err'] = 'Please select a category.';
            } else {
                // Validate for saving draft again
                if (empty($data['subject']) && empty($data['description']) && empty($data['category']) && empty($data['attachment_filename'])) {
                    $data['subject_err'] = 'Please enter at least some information or attach a file to save as a draft';
                }
            }

            // Check errors
            if (empty($data['subject_err']) && empty($data['description_err']) && empty($data['category_err']) && (empty($data['attachment_err']) || $data['is_draft'])) {
                $ticketData = [
                    'id' => $id,
                    'subject' => $data['subject'],
                    'description' => $data['description'],
                    'category' => $data['category'],
                    'priority' => $data['priority'],
                    'status' => $data['is_draft'] ? 'draft' : 'open', // Update status if submitting
                    'attachment_filename' => $data['attachment_filename']
                ];

                if ($this->supportModel->updateTicketDraft($ticketData)) {
                    if ($data['is_draft']) {
                        flash('ticket_message', 'Draft updated successfully');
                        redirect('support/drafts');
                    } else {
                        flash('ticket_message', 'Ticket submitted successfully from draft');
                        redirect('support');
                    }
                } else {
                    if ($uploadedFileName) $this->fileUploadHelper->delete($uploadedFileName); // Rollback upload on DB error
                    flash('ticket_message', 'Something went wrong updating the draft', 'alert alert-danger');
                    // Need to repopulate data for the view
                    $data['draft'] = $draft; // Pass the original draft data back
                    $this->view('users/support/edit_draft', $data);
                }
            } else {
                if ($uploadedFileName && !$data['is_draft']) $this->fileUploadHelper->delete($uploadedFileName); // Rollback upload on validation error
                $data['draft'] = $draft; // Pass the original draft data back
                $this->view('users/support/edit_draft', $data);
            }
        } else {
            // Initial GET request to load the edit form
            $data = [
                'title' => 'Edit Draft Ticket',
                'description' => 'Update your draft support ticket',
                'id' => $id,
                'draft' => $draft, // Pass draft data to the view
                'subject' => $draft->subject,
                'description' => $draft->description,
                'category' => $draft->category,
                'priority' => $draft->priority,
                'attachment_filename' => $draft->attachment_filename,
                'subject_err' => '',
                'description_err' => '',
                'category_err' => '',
                'priority_err' => '',
                'attachment_err' => ''
            ];
            $this->view('users/support/edit_draft', $data);
        }
    }

    /**
     * Display FAQ page
     */
    public function faq()
    {
        $data = [
            'title' => 'Frequently Asked Questions',
            'description' => 'Find answers to common questions about our platform and services',
            'categories' => [
                'general' => [
                    [
                        'question' => 'What is LenSI?',
                        'answer' => 'LenSI is a platform connecting freelance professionals with clients looking for quality services. Our marketplace allows freelancers to showcase their skills and clients to find the perfect match for their projects.'
                    ],
                    [
                        'question' => 'How do I create an account?',
                        'answer' => 'To create an account, click on the "Join" button in the top right corner of the homepage. You can register as either a freelancer or a client, depending on your needs.'
                    ],
                    [
                        'question' => 'Is registration free?',
                        'answer' => 'Yes, basic registration is completely free. We offer premium plans with additional features, but you can get started without any payment.'
                    ],
                    [
                        'question' => 'Can I use LenSI on mobile devices?',
                        'answer' => 'Yes, our platform is fully responsive and works on desktops, tablets, and smartphones, allowing you to manage your projects on the go.'
                    ]
                ],
                'freelancer' => [
                    [
                        'question' => 'How do I create my freelancer profile?',
                        'answer' => 'After registering as a freelancer, you can create your profile by adding your skills, experience, portfolio, and setting your service rates. The more complete your profile, the more likely clients are to hire you.'
                    ],
                    [
                        'question' => 'How do I get paid for my work?',
                        'answer' => 'We offer several secure payment methods. Once a client approves your work, the payment is released from escrow to your LenSI account, and you can withdraw it to your bank account or other payment methods.'
                    ],
                    [
                        'question' => 'What are Connects and how do they work?',
                        'answer' => 'Connects are tokens used to apply for jobs. Each job application requires a certain number of Connects. Free accounts get a limited number of Connects per month, while premium members get more.'
                    ],
                    [
                        'question' => 'How much does LenSI charge freelancers?',
                        'answer' => 'LenSI charges a service fee of 10% on all projects. This fee covers platform maintenance, payment processing, and ongoing improvements.'
                    ]
                ],
                'client' => [
                    [
                        'question' => 'How do I post a job?',
                        'answer' => 'After creating a client account, click on "Post a Job" in the navigation menu. Fill out the job details, including requirements, budget, and timeline, then post it to start receiving proposals from freelancers.'
                    ],
                    [
                        'question' => 'How do I choose the right freelancer?',
                        'answer' => 'Review freelancer profiles, portfolios, ratings, and reviews. You can also interview potential candidates through our messaging system before making your decision.'
                    ],
                    [
                        'question' => 'Is my payment secure?',
                        'answer' => 'Yes, we use an escrow system to protect both clients and freelancers. You fund the project before it begins, but the payment is only released to the freelancer when you approve the completed work.'
                    ],
                    [
                        'question' => 'What if I\'m not satisfied with the work?',
                        'answer' => 'If you\'re not satisfied, you can request revisions according to your agreement with the freelancer. If issues persist, our dispute resolution team can help mediate.'
                    ]
                ],
                'technical' => [
                    [
                        'question' => 'How do I reset my password?',
                        'answer' => 'Go to the login page and click on "Forgot Password". Enter your email address, and we\'ll send you instructions to reset your password.'
                    ],
                    [
                        'question' => 'Is my personal information secure?',
                        'answer' => 'Yes, we use industry-standard encryption to protect your personal and payment information. We never share your data with third parties without your permission.'
                    ],
                    [
                        'question' => 'Can I delete my account?',
                        'answer' => 'Yes, you can delete your account from your account settings. Note that this action is permanent and will remove all your data from our platform.'
                    ],
                    [
                        'question' => 'What browsers are supported?',
                        'answer' => 'LenSI works best on modern browsers like Chrome, Firefox, Safari, and Edge. We recommend keeping your browser updated for the best experience.'
                    ]
                ],
                'billing' => [
                    [
                        'question' => 'What payment methods do you accept?',
                        'answer' => 'We accept credit cards (Visa, Mastercard, American Express), PayPal, and bank transfers for most regions.'
                    ],
                    [
                        'question' => 'How do refunds work?',
                        'answer' => 'Refund policies depend on the specific situation and project terms. Generally, if a freelancer has not started work, you can get a full refund. For disputes, our customer service team will review the case.'
                    ],
                    [
                        'question' => 'Are there any hidden fees?',
                        'answer' => 'No, all fees are clearly displayed before you make any payment. We believe in transparent pricing with no surprises.'
                    ],
                    [
                        'question' => 'How do I upgrade or downgrade my membership?',
                        'answer' => 'You can change your membership plan in your account settings under "Membership". Changes typically take effect at the start of your next billing cycle.'
                    ]
                ]
            ]
        ];

        $this->view('users/support/faq', $data);
    }

    /**
     * Display Contact Us page
     */
    public function contact()
    {
        $data = [
            'title' => 'Contact Us',
            'description' => 'Get in touch with our support team',
        ];

        $this->view('users/support/contact', $data);
    }

    /**
     * Process contact form submissions
     */
    public function submitContact()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Replace deprecated FILTER_SANITIZE_STRING with htmlspecialchars
            $postData = [];
            foreach ($_POST as $key => $value) {
                $postData[$key] = htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
            }

            // Initialize data array
            $data = [
                'name' => trim($postData['name'] ?? ''),
                'email' => trim($postData['email'] ?? ''),
                'subject' => trim($postData['subject'] ?? ''),
                'message' => trim($postData['message'] ?? ''),
                'inquiry_type' => trim($postData['inquiry_type'] ?? 'general'),
                'send_copy' => isset($_POST['send_copy']),
                'error' => false,
                'user_id' => $_SESSION['user_id'],
                'priority' => 'medium',
                'browser_info' => null,
                'billing_details' => null,
                'business_details' => null
            ];

            // Add any additional form fields based on inquiry type
            switch ($data['inquiry_type']) {
                case 'technical':
                    $data['priority'] = trim($postData['priority'] ?? 'medium');
                    $data['browser_info'] = trim($postData['browser_info'] ?? '');
                    break;
                case 'billing':
                    $data['priority'] = trim($postData['priority'] ?? 'medium');
                    $billingDetails = [
                        'billing_type' => trim($postData['billing_type'] ?? ''),
                        'order_id' => trim($postData['order_id'] ?? '')
                    ];
                    $data['billing_details'] = json_encode($billingDetails);
                    break;
                case 'business':
                    $data['priority'] = trim($postData['priority'] ?? 'medium');
                    $businessDetails = [
                        'company' => trim($postData['company'] ?? ''),
                        'position' => trim($postData['position'] ?? ''),
                        'business_type' => trim($postData['business_type'] ?? ''),
                        'website' => trim($postData['website'] ?? '')
                    ];
                    $data['business_details'] = json_encode($businessDetails);
                    break;
            }

            // Validate inputs
            if (empty($data['name'])) {
                $data['error'] = true;
                flash('contact_message', 'Please enter your name', 'alert alert-danger');
            } elseif (empty($data['email']) || !filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['error'] = true;
                flash('contact_message', 'Please enter a valid email address', 'alert alert-danger');
            } elseif (empty($data['subject'])) {
                $data['error'] = true;
                flash('contact_message', 'Please enter a subject', 'alert alert-danger');
            } elseif (empty($data['message'])) {
                $data['error'] = true;
                flash('contact_message', 'Please enter your message', 'alert alert-danger');
            }

            if ($data['error']) {
                // Redirect back to the contact form with the error message
                redirect('support/contact#' . $data['inquiry_type'] . '-inquiries');
                return;
            }

            // Process the contact form submission using the Contact model
            $contactId = $this->contactModel->submitContactMessage($data);

            if ($contactId) {
                // Also create a support ticket for the contact form submission
                $ticketData = [
                    'user_id' => $_SESSION['user_id'],
                    'subject' => '[' . ucfirst($data['inquiry_type']) . '] ' . $data['subject'],
                    'description' => $data['message'] . "\n\n--- From Contact Form: #" . $contactId . " ---",
                    'category' => $data['inquiry_type'],
                    'priority' => $data['priority'],
                    'is_draft' => false,
                    'attachment_filename' => null
                ];

                // Add additional context to the ticket description based on inquiry type
                switch ($data['inquiry_type']) {
                    case 'technical':
                        if (!empty($data['browser_info'])) {
                            $ticketData['description'] .= "\n\nBrowser/Device Information: " . $data['browser_info'];
                        }
                        break;
                    case 'billing':
                        $billingDetails = json_decode($data['billing_details'], true);
                        if (!empty($billingDetails['order_id'])) {
                            $ticketData['description'] .= "\n\nOrder/Transaction ID: " . $billingDetails['order_id'];
                        }
                        if (!empty($billingDetails['billing_type'])) {
                            $ticketData['description'] .= "\n\nBilling Issue Type: " . $billingDetails['billing_type'];
                        }
                        break;
                    case 'business':
                        $businessDetails = json_decode($data['business_details'], true);
                        if (!empty($businessDetails['company'])) {
                            $ticketData['description'] .= "\n\nCompany: " . $businessDetails['company'];
                        }
                        if (!empty($businessDetails['position'])) {
                            $ticketData['description'] .= "\n\nPosition: " . $businessDetails['position'];
                        }
                        if (!empty($businessDetails['website'])) {
                            $ticketData['description'] .= "\n\nWebsite: " . $businessDetails['website'];
                        }
                        if (!empty($businessDetails['business_type'])) {
                            $ticketData['description'] .= "\n\nInquiry Type: " . $businessDetails['business_type'];
                        }
                        break;
                }

                // Create a support ticket linked to the contact submission
                $ticketResult = $this->supportModel->createTicket($ticketData);

                // If the user wants a copy, we would send an email here in a real application
                if ($data['send_copy']) {
                    // Email sending logic would go here 
                    // For now, we'll just note it in the flash message
                    flash('contact_message', 'Your message has been sent successfully and a copy has been emailed to you. We\'ll get back to you shortly.', 'alert alert-success');
                } else {
                    flash('contact_message', 'Your message has been sent successfully. We\'ll get back to you shortly.', 'alert alert-success');
                }

                redirect('support/contact');
            } else {
                flash('contact_message', 'There was an error sending your message. Please try again.', 'alert alert-danger');
                redirect('support/contact#' . $data['inquiry_type'] . '-inquiries');
            }
        } else {
            // If not POST request, redirect to the contact form
            redirect('support/contact');
        }
    }

    /**
     * Import tickets from CSV or Excel file
     */
    public function import()
    {
        // Check if POST request (form submission for import)
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (!isset($_FILES['import_file']) || $_FILES['import_file']['error'] !== UPLOAD_ERR_OK) {
                flash('ticket_message', 'No file uploaded or upload error occurred', 'alert alert-danger');
                redirect('support/import');
                return;
            }

            // Get uploaded file details
            $file = $_FILES['import_file'];
            $fileName = $file['name'];
            $tmpName = $file['tmp_name'];
            $fileSize = $file['size'];
            $fileType = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

            // Validate file type
            if ($fileType != 'csv' && $fileType != 'xlsx') {
                flash('ticket_message', 'Invalid file type. Please upload a CSV or XLSX file', 'alert alert-danger');
                redirect('support/import');
                return;
            }

            // Validate file size (10MB limit)
            if ($fileSize > 10 * 1024 * 1024) {
                flash('ticket_message', 'File size exceeds the 10MB limit', 'alert alert-danger');
                redirect('support/import');
                return;
            }

            // Process the import based on file type
            $importData = [];
            $totalRows = 0;
            $successCount = 0;
            $errorCount = 0;
            $errors = [];

            try {
                if ($fileType == 'csv') {
                    // Process CSV file
                    $handle = fopen($tmpName, 'r');
                    
                    if ($handle !== false) {
                        // Read header row first
                        $headerRow = fgetcsv($handle);
                        
                        // Convert header row to lowercase for case-insensitive matching
                        $headerRow = array_map('strtolower', $headerRow);
                        
                        // Check for required columns
                        $requiredColumns = ['subject', 'description', 'category', 'priority'];
                        $missingColumns = [];
                        
                        foreach ($requiredColumns as $column) {
                            if (!in_array($column, $headerRow)) {
                                $missingColumns[] = $column;
                            }
                        }
                        
                        if (!empty($missingColumns)) {
                            flash('ticket_message', 'Missing required columns: ' . implode(', ', $missingColumns), 'alert alert-danger');
                            redirect('support/import');
                            return;
                        }
                        
                        // Get indexes for required columns
                        $subjectIndex = array_search('subject', $headerRow);
                        $descIndex = array_search('description', $headerRow);
                        $categoryIndex = array_search('category', $headerRow);
                        $priorityIndex = array_search('priority', $headerRow);
                        
                        // Read data rows
                        while (($row = fgetcsv($handle)) !== false) {
                            $totalRows++;
                            
                            // Skip empty rows
                            if (empty($row[0]) && count(array_filter($row)) === 0) {
                                continue;
                            }
                            
                            // Validate row data
                            $rowValid = true;
                            $rowErrors = [];
                            
                            // Check subject
                            if (empty($row[$subjectIndex])) {
                                $rowValid = false;
                                $rowErrors[] = 'Subject is required';
                            }
                            
                            // Check description
                            if (empty($row[$descIndex])) {
                                $rowValid = false;
                                $rowErrors[] = 'Description is required';
                            }
                            
                            // Check category
                            $validCategories = ['technical', 'billing', 'account', 'connects', 'proposals', 'feature', 'other'];
                            if (empty($row[$categoryIndex]) || !in_array(strtolower($row[$categoryIndex]), $validCategories)) {
                                $rowValid = false;
                                $rowErrors[] = 'Invalid category';
                            }
                            
                            // Check priority
                            $validPriorities = ['high', 'medium', 'low'];
                            if (empty($row[$priorityIndex]) || !in_array(strtolower($row[$priorityIndex]), $validPriorities)) {
                                $rowValid = false;
                                $rowErrors[] = 'Invalid priority';
                            }
                            
                            if ($rowValid) {
                                // Create ticket
                                $ticketData = [
                                    'user_id' => $_SESSION['user_id'],
                                    'subject' => htmlspecialchars(trim($row[$subjectIndex]), ENT_QUOTES, 'UTF-8'),
                                    'description' => htmlspecialchars(trim($row[$descIndex]), ENT_QUOTES, 'UTF-8'),
                                    'category' => strtolower(trim($row[$categoryIndex])),
                                    'priority' => strtolower(trim($row[$priorityIndex])),
                                    'is_draft' => false,
                                    'attachment_filename' => null
                                ];
                                
                                if ($this->supportModel->createTicket($ticketData)) {
                                    $successCount++;
                                } else {
                                    $errorCount++;
                                    $errors[] = "Failed to import row " . ($totalRows + 1);
                                }
                            } else {
                                $errorCount++;
                                $errors[] = "Row " . ($totalRows + 1) . ": " . implode(', ', $rowErrors);
                            }
                            
                            // Limit to 100 tickets per import
                            if ($totalRows >= 100) {
                                break;
                            }
                        }
                        
                        fclose($handle);
                    } else {
                        throw new Exception('Failed to open CSV file');
                    }
                } else if ($fileType == 'xlsx') {
                    // For XLSX handling, we'd need a library like PhpSpreadsheet
                    // This is a simplified implementation
                    flash('ticket_message', 'XLSX import is not fully implemented yet. Please use CSV format.', 'alert alert-warning');
                    redirect('support/import');
                    return;
                }
                
                // Generate import summary message
                if ($successCount > 0) {
                    $message = "Successfully imported {$successCount} ticket(s). ";
                    if ($errorCount > 0) {
                        $message .= "Failed to import {$errorCount} ticket(s).";
                        if (count($errors) > 0) {
                            $message .= " First few errors: " . implode('; ', array_slice($errors, 0, 3));
                        }
                        flash('ticket_message', $message, 'alert alert-warning');
                    } else {
                        flash('ticket_message', $message, 'alert alert-success');
                    }
                } else {
                    $message = "No tickets were imported. ";
                    if (count($errors) > 0) {
                        $message .= "Errors: " . implode('; ', array_slice($errors, 0, 3));
                    }
                    flash('ticket_message', $message, 'alert alert-danger');
                }
                
                redirect('support');
                
            } catch (Exception $e) {
                flash('ticket_message', 'Error processing import: ' . $e->getMessage(), 'alert alert-danger');
                redirect('support/import');
                return;
            }
        } else {
            // GET request - display import page
            $data = [
                'title' => 'Import Support Tickets',
                'description' => 'Bulk import support tickets from CSV or Excel file'
            ];
            
            $this->view('users/support/import', $data);
        }
    }

    /**
     * Download import template
     */
    public function downloadTemplate()
    {
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="support_tickets_import_template.csv"');
        
        // Create a file pointer
        $output = fopen('php://output', 'w');
        
        // Write header row
        fputcsv($output, ['subject', 'description', 'category', 'priority']);
        
        // Write sample rows
        fputcsv($output, ['Login Issue', 'I cannot log into my account. It says invalid credentials.', 'technical', 'high']);
        fputcsv($output, ['Billing Question', 'I was charged twice for my subscription this month.', 'billing', 'medium']);
        
        // Close the file pointer
        fclose($output);
        exit;
    }
}
