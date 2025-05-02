<?php

/**
 * DashboardController Class
 * Handles dashboard functionality and data processing
 */
// Include the DashboardService class
require_once APPROOT . '/helpers/DashboardService.php';

class DashboardController extends Controller
{
    private $dashboardModel;
    private $dashboardService;
    private $forumModel;
    private $userModel;
    private $supportModel;
    private $faqModel;

    public function __construct()
    {
        // Initialize dashboard model
        $this->dashboardModel = $this->model('DashboardModel');
        $this->dashboardService = new DashboardService();
        $this->forumModel = $this->model('Forum');
        $this->userModel = $this->model('User');
        $this->supportModel = $this->model('Support');
        $this->faqModel = $this->model('Faq');

        // Check if user is authorized to access the dashboard
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_account_type']) || $_SESSION['user_account_type'] !== 'admin') {
            redirect('users/login');
        }
    }

    // Main dashboard index page
    public function index()
    {
        // Get analytics data for the dashboard
        $analyticsData = $this->dashboardModel->getAnalyticsData();

        // Get recent users for the dashboard table
        $users = $this->dashboardModel->getUsersData();

        // Format analytics data for charts
        $visitChartData = [];
        foreach ($analyticsData['visitHistory'] as $visit) {
            $date = new DateTime($visit['date']);
            $visitChartData[] = [
                'day' => $date->format('D'),
                'value' => $visit['visits']
            ];
        }

        $userDistributionData = $analyticsData['userDistribution'];

        // Get recent activity logs
        $activityLogs = $this->dashboardService->getRecentActivityLogs(5);

        // Log this dashboard view
        $this->dashboardService->logActivity('view', 'dashboard');

        // Prepare data for the view
        $data = [
            'title' => 'Admin Dashboard',
            'description' => 'Admin Dashboard',
            'analyticsData' => $analyticsData,
            'users' => $users,
            'visitChartData' => json_encode($visitChartData),
            'userDistributionData' => json_encode($userDistributionData),
            'activityLogs' => $activityLogs
        ];

        $this->view('dashboard/index', $data);
    }

    // User Management page
    public function users()
    {
        $users = $this->dashboardModel->getUsersData();

        // Log this user management view
        $this->dashboardService->logActivity('view', 'user_management');

        $data = [
            'title' => 'User Management',
            'description' => 'Manage users',
            'active' => 'users',
            'users' => $users
        ];

        $this->view('dashboard/users', $data);
    }

    // Blog Management page
    public function blog()
    {
        $posts = $this->dashboardModel->getBlogPostsData();

        // Log this blog management view
        $this->dashboardService->logActivity('view', 'blog_management');

        $data = [
            'title' => 'Blog Management',
            'description' => 'Manage blog posts',
            'posts' => $posts
        ];

        $this->view('dashboard/blog', $data);
    }

    // Support Tickets page
    public function support()
    {
        $tickets = $this->dashboardModel->getSupportTicketsData();

        // Get ticket counts by status for stats
        $openCount = 0;
        $pendingCount = 0;
        $answeredCount = 0;
        $closedCount = 0;

        foreach ($tickets as $ticket) {
            // Check if $ticket is an array or object and access status accordingly
            $status = is_array($ticket) ? $ticket['status'] : $ticket->status;

            switch ($status) {
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

        // Log this support tickets view
        $this->dashboardService->logActivity('view', 'support_tickets');

        $data = [
            'title' => 'Support Tickets',
            'description' => 'Manage support tickets',
            'active' => 'support',
            'active_parent' => 'support', // Added for proper sidebar highlighting
            'tickets' => $tickets,
            'ticketStats' => [
                'total' => count($tickets),
                'open' => $openCount,
                'pending' => $pendingCount,
                'answered' => $answeredCount,
                'closed' => $closedCount
            ]
        ];

        $this->view('dashboard/support', $data);
    }

    // View a specific ticket
    public function viewTicket($id = null)
    {
        // Check if ID is set
        if (!$id) {
            flash('ticket_message', 'Invalid ticket ID', 'alert alert-danger');
            redirect('dashboard/support');
        }

        // Get ticket details
        $ticket = $this->supportModel->getTicketById($id);

        // Verify ticket exists
        if (!$ticket) {
            flash('ticket_message', 'Ticket not found', 'alert alert-danger');
            redirect('dashboard/support');
        }

        // Get ticket replies
        $replies = $this->supportModel->getTicketReplies($id);

        // Log this ticket view
        $this->dashboardService->logActivity('view', 'ticket', ['ticket_id' => $id]);

        $data = [
            'title' => 'View Ticket #' . $id,
            'description' => 'View and manage support ticket',
            'active' => 'tickets', // Changed to be more specific
            'active_parent' => 'support', // Added for proper sidebar highlighting
            'ticket' => $ticket,
            'replies' => $replies
        ];

        $this->view('dashboard/viewTicket', $data);
    }

    // Add a reply to a ticket
    public function addTicketReply($id = null)
    {
        // Check if ID is set
        if (!$id) {
            flash('ticket_message', 'Invalid ticket ID', 'alert alert-danger');
            redirect('dashboard/support');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            // Get ticket details
            $ticket = $this->supportModel->getTicketById($id);

            // Verify ticket exists
            if (!$ticket) {
                flash('ticket_message', 'Ticket not found', 'alert alert-danger');
                redirect('dashboard/support');
            }

            // Prepare data
            $data = [
                'ticket_id' => $id,
                'user_id' => $_SESSION['user_id'],
                'message' => trim($_POST['message']),
                'is_admin' => 1, // Mark as admin reply
                'message_err' => ''
            ];

            // Validate message
            if (empty($data['message'])) {
                $data['message_err'] = 'Please enter a message';
            }

            // Make sure errors are empty
            if (empty($data['message_err'])) {
                // Add reply
                if ($this->supportModel->addReply($data)) {
                    // Update ticket status if requested
                    if (isset($_POST['mark_as_answered']) && $_POST['mark_as_answered'] == 1) {
                        $this->supportModel->updateTicketStatus($id, 'answered');
                    } else {
                        // If not marked as answered, update to pending
                        if ($ticket->status == 'open') {
                            $this->supportModel->updateTicketStatus($id, 'pending');
                        }
                    }

                    // Log this reply
                    $this->dashboardService->logActivity('action', 'ticket_reply', ['ticket_id' => $id]);

                    flash('ticket_message', 'Reply added successfully', 'alert alert-success');
                    redirect('dashboard/viewTicket/' . $id);
                } else {
                    flash('ticket_message', 'Something went wrong', 'alert alert-danger');
                    redirect('dashboard/viewTicket/' . $id);
                }
            } else {
                // Load view with errors
                $data['ticket'] = $ticket;
                $data['replies'] = $this->supportModel->getTicketReplies($id);
                $data['title'] = 'View Ticket #' . $id;
                $data['description'] = 'View and manage support ticket';
                $data['active'] = 'support';

                $this->view('dashboard/viewTicket', $data);
            }
        } else {
            redirect('dashboard/viewTicket/' . $id);
        }
    }

    // Update ticket status
    public function updateTicketStatus($id = null, $status = null)
    {
        // Check if ID and status are set
        if (!$id || !$status || !in_array($status, ['open', 'pending', 'answered', 'closed'])) {
            flash('ticket_message', 'Invalid request', 'alert alert-danger');
            redirect('dashboard/support');
        }

        // Get ticket details
        $ticket = $this->supportModel->getTicketById($id);

        // Verify ticket exists
        if (!$ticket) {
            flash('ticket_message', 'Ticket not found', 'alert alert-danger');
            redirect('dashboard/support');
        }

        // Update ticket status
        if ($this->supportModel->updateTicketStatus($id, $status)) {
            // Log this status update
            $this->dashboardService->logActivity('action', 'ticket_status_update', [
                'ticket_id' => $id,
                'old_status' => $ticket->status,
                'new_status' => $status
            ]);

            flash('ticket_message', 'Ticket status updated successfully', 'alert alert-success');
        } else {
            flash('ticket_message', 'Something went wrong', 'alert alert-danger');
        }

        // Redirect back to the ticket or support page
        if (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], 'viewTicket')) {
            redirect('dashboard/viewTicket/' . $id);
        } else {
            redirect('dashboard/support');
        }
    }

    // Delete a ticket
    public function deleteTicket($id = null)
    {
        // Check if ID is set
        if (!$id) {
            flash('ticket_message', 'Invalid ticket ID', 'alert alert-danger');
            redirect('dashboard/support');
        }

        // Get ticket details
        $ticket = $this->supportModel->getTicketById($id);

        // Verify ticket exists
        if (!$ticket) {
            flash('ticket_message', 'Ticket not found', 'alert alert-danger');
            redirect('dashboard/support');
        }

        // Delete ticket
        if ($this->supportModel->deleteTicket($id)) {
            // Log this deletion
            $this->dashboardService->logActivity('action', 'ticket_delete', ['ticket_id' => $id]);

            flash('ticket_message', 'Ticket deleted successfully', 'alert alert-success');
        } else {
            flash('ticket_message', 'Something went wrong', 'alert alert-danger');
        }

        redirect('dashboard/support');
    }

    // Get ticket statistics (for AJAX)
    public function getTicketStats()
    {
        // Get all tickets
        $tickets = $this->dashboardModel->getSupportTicketsData();

        // Get ticket counts by status
        $openCount = 0;
        $pendingCount = 0;
        $answeredCount = 0;
        $closedCount = 0;

        foreach ($tickets as $ticket) {
            // Check if $ticket is an array or object and access status accordingly
            $status = is_array($ticket) ? $ticket['status'] : $ticket->status;

            switch ($status) {
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

        // Return JSON response
        header('Content-Type: application/json');
        echo json_encode([
            'total' => count($tickets),
            'open' => $openCount,
            'pending' => $pendingCount,
            'answered' => $answeredCount,
            'closed' => $closedCount
        ]);
        exit;
    }

    // FAQ Management page
    public function faq()
    {
        $faqs = $this->faqModel->getAllFaqs();
        $categories = $this->faqModel->getAllCategories();

        // Log this FAQ management view
        $this->dashboardService->logActivity('view', 'faq_management');

        $data = [
            'title' => 'FAQ Management',
            'description' => 'Manage frequently asked questions',
            'active' => 'faq',
            'active_parent' => 'support', // Added for proper sidebar highlighting
            'faqs' => $faqs,
            'categories' => $categories
        ];

        $this->view('dashboard/faq', $data);
    }

    // Add a new FAQ
    public function addFaq()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            // Prepare data
            $data = [
                'question' => trim($_POST['question']),
                'answer' => trim($_POST['answer']),
                'category' => trim($_POST['category']),
                'order' => isset($_POST['order']) ? (int)$_POST['order'] : 0,
                'is_published' => isset($_POST['is_published']) ? 1 : 0,
                'question_err' => '',
                'answer_err' => '',
                'category_err' => ''
            ];

            // Validate data
            if (empty($data['question'])) {
                $data['question_err'] = 'Please enter a question';
            }

            if (empty($data['answer'])) {
                $data['answer_err'] = 'Please enter an answer';
            }

            if (empty($data['category'])) {
                $data['category_err'] = 'Please select a category';
            }

            // Make sure errors are empty
            if (empty($data['question_err']) && empty($data['answer_err']) && empty($data['category_err'])) {
                // Add FAQ
                if ($this->faqModel->addFaq($data)) {
                    // Log this addition
                    $this->dashboardService->logActivity('action', 'faq_add');

                    flash('faq_message', 'FAQ added successfully', 'alert alert-success');
                    redirect('dashboard/faq');
                } else {
                    flash('faq_message', 'Something went wrong', 'alert alert-danger');
                    redirect('dashboard/faq');
                }
            } else {
                // Load view with errors
                $data['title'] = 'FAQ Management';
                $data['description'] = 'Manage frequently asked questions';
                $data['active'] = 'support';
                $data['faqs'] = $this->faqModel->getAllFaqs();
                $data['categories'] = $this->faqModel->getAllCategories();

                $this->view('dashboard/faq', $data);
            }
        } else {
            redirect('dashboard/faq');
        }
    }

    // Edit an FAQ
    public function editFaq($id = null)
    {
        // Check if ID is set
        if (!$id) {
            flash('faq_message', 'Invalid FAQ ID', 'alert alert-danger');
            redirect('dashboard/faq');
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            // Prepare data
            $data = [
                'id' => $id,
                'question' => trim($_POST['question']),
                'answer' => trim($_POST['answer']),
                'category' => trim($_POST['category']),
                'order' => isset($_POST['order']) ? (int)$_POST['order'] : 0,
                'is_published' => isset($_POST['is_published']) ? 1 : 0,
                'question_err' => '',
                'answer_err' => '',
                'category_err' => ''
            ];

            // Validate data
            if (empty($data['question'])) {
                $data['question_err'] = 'Please enter a question';
            }

            if (empty($data['answer'])) {
                $data['answer_err'] = 'Please enter an answer';
            }

            if (empty($data['category'])) {
                $data['category_err'] = 'Please select a category';
            }

            // Make sure errors are empty
            if (empty($data['question_err']) && empty($data['answer_err']) && empty($data['category_err'])) {
                // Update FAQ
                if ($this->faqModel->updateFaq($data)) {
                    // Log this update
                    $this->dashboardService->logActivity('action', 'faq_update', ['faq_id' => $id]);

                    flash('faq_message', 'FAQ updated successfully', 'alert alert-success');
                    redirect('dashboard/faq');
                } else {
                    flash('faq_message', 'Something went wrong', 'alert alert-danger');
                    redirect('dashboard/faq');
                }
            } else {
                // Load view with errors
                $data['title'] = 'Edit FAQ';
                $data['description'] = 'Edit frequently asked question';
                $data['active'] = 'faq';
                $data['active_parent'] = 'support'; // Added for proper sidebar highlighting
                $data['categories'] = $this->faqModel->getAllCategories();

                $this->view('dashboard/editFaq', $data);
            }
        } else {
            // Get FAQ details
            $faq = $this->faqModel->getFaqById($id);

            // Verify FAQ exists
            if (!$faq) {
                flash('faq_message', 'FAQ not found', 'alert alert-danger');
                redirect('dashboard/faq');
            }

            $data = [
                'title' => 'Edit FAQ',
                'description' => 'Edit frequently asked question',
                'active' => 'faq',
                'active_parent' => 'support', // Added for proper sidebar highlighting
                'id' => $id,
                'question' => $faq->question,
                'answer' => $faq->answer,
                'category' => $faq->category,
                'order' => $faq->display_order,
                'is_published' => $faq->is_published,
                'categories' => $this->faqModel->getAllCategories()
            ];

            $this->view('dashboard/editFaq', $data);
        }
    }

    // Delete an FAQ
    public function deleteFaq($id = null)
    {
        // Check if ID is set
        if (!$id) {
            flash('faq_message', 'Invalid FAQ ID', 'alert alert-danger');
            redirect('dashboard/faq');
        }

        // Get FAQ details
        $faq = $this->faqModel->getFaqById($id);

        // Verify FAQ exists
        if (!$faq) {
            flash('faq_message', 'FAQ not found', 'alert alert-danger');
            redirect('dashboard/faq');
        }

        // Delete FAQ
        if ($this->faqModel->deleteFaq($id)) {
            // Log this deletion
            $this->dashboardService->logActivity('action', 'faq_delete', ['faq_id' => $id]);

            flash('faq_message', 'FAQ deleted successfully', 'alert alert-success');
        } else {
            flash('faq_message', 'Something went wrong', 'alert alert-danger');
        }

        redirect('dashboard/faq');
    }

    // Add a new FAQ category
    public function addFaqCategory()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

            // Prepare data
            $data = [
                'name' => trim($_POST['name']),
                'name_err' => ''
            ];

            // Validate data
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter a category name';
            }

            // Make sure errors are empty
            if (empty($data['name_err'])) {
                // Add category
                if ($this->faqModel->addCategory($data)) {
                    // Log this addition
                    $this->dashboardService->logActivity('action', 'faq_category_add');

                    flash('faq_message', 'Category added successfully', 'alert alert-success');
                } else {
                    flash('faq_message', 'Something went wrong', 'alert alert-danger');
                }

                redirect('dashboard/faq');
            } else {
                // Load view with errors
                $data['title'] = 'FAQ Management';
                $data['description'] = 'Manage frequently asked questions';
                $data['active'] = 'support';
                $data['faqs'] = $this->faqModel->getAllFaqs();
                $data['categories'] = $this->faqModel->getAllCategories();

                $this->view('dashboard/faq', $data);
            }
        } else {
            redirect('dashboard/faq');
        }
    }

    // Delete an FAQ category
    public function deleteFaqCategory($id = null)
    {
        // Check if ID is set
        if (!$id) {
            flash('faq_message', 'Invalid category ID', 'alert alert-danger');
            redirect('dashboard/faq');
        }

        // Get category details
        $category = $this->faqModel->getCategoryById($id);

        // Verify category exists
        if (!$category) {
            flash('faq_message', 'Category not found', 'alert alert-danger');
            redirect('dashboard/faq');
        }

        // Check if category has FAQs
        if ($this->faqModel->categoryHasFaqs($id)) {
            flash('faq_message', 'Cannot delete category with associated FAQs', 'alert alert-danger');
            redirect('dashboard/faq');
        }

        // Delete category
        if ($this->faqModel->deleteCategory($id)) {
            // Log this deletion
            $this->dashboardService->logActivity('action', 'faq_category_delete', ['category_id' => $id]);

            flash('faq_message', 'Category deleted successfully', 'alert alert-success');
        } else {
            flash('faq_message', 'Something went wrong', 'alert alert-danger');
        }

        redirect('dashboard/faq');
    }

    // Settings page
    public function settings()
    {
        $data = [
            'title' => 'Dashboard Settings',
            'description' => 'Manage dashboard settings'
        ];

        $this->view('dashboard/settings', $data);
    }

    // Community Management page
    public function community()
    {
        // Get active section from query parameter (forums, groups, resources)
        $section = isset($_GET['section']) ? $_GET['section'] : 'forums';

        // Validate section
        if (!in_array($section, ['forums', 'groups', 'resources'])) {
            $section = 'forums';
        }

        // Get forum statistics
        $totalTopics = $this->forumModel->countAllTopics();
        $totalReplies = $this->forumModel->countAllReplies();
        $recentTopics = $this->forumModel->getRecentTopics(10);
        $recentReplies = $this->forumModel->getRecentReplies(10);
        $reportedContent = $this->forumModel->getReportedContent();
        $categories = $this->forumModel->getCategories();

        // Log this community management view
        $this->dashboardService->logActivity('view', 'community_management', ['section' => $section]);

        $data = [
            'title' => 'Community Management',
            'description' => 'Manage community forums and content',
            'active' => $section,
            'active_parent' => 'community', // Added for proper sidebar highlighting
            'section' => $section,
            'totalTopics' => $totalTopics,
            'totalReplies' => $totalReplies,
            'recentTopics' => $recentTopics,
            'recentReplies' => $recentReplies,
            'reportedContent' => $reportedContent,
            'categories' => $categories
        ];

        $this->view('dashboard/community', $data);
    }

    // Handle topic and reply actions
    public function manageTopic($action, $id = null)
    {
        if (!$id || !in_array($action, ['pin', 'unpin', 'close', 'open', 'delete'])) {
            flash('dashboard_message', 'Invalid request', 'alert alert-danger');
            redirect('dashboard/community');
        }

        $success = false;
        $message = '';

        switch ($action) {
            case 'pin':
                $success = $this->forumModel->updateTopicStatus($id, 'pinned');
                $message = 'Topic pinned successfully';
                break;
            case 'unpin':
                $success = $this->forumModel->updateTopicStatus($id, 'open');
                $message = 'Topic unpinned successfully';
                break;
            case 'close':
                $success = $this->forumModel->updateTopicStatus($id, 'closed');
                $message = 'Topic closed successfully';
                break;
            case 'open':
                $success = $this->forumModel->updateTopicStatus($id, 'open');
                $message = 'Topic reopened successfully';
                break;
            case 'delete':
                $success = $this->forumModel->deleteTopic($id);
                $message = 'Topic deleted successfully';
                break;
        }

        if ($success) {
            flash('dashboard_message', $message, 'alert alert-success');
            $this->dashboardService->logActivity('action', 'topic_' . $action, ['topic_id' => $id]);
        } else {
            flash('dashboard_message', 'Failed to ' . $action . ' topic', 'alert alert-danger');
        }

        redirect('dashboard/community');
    }

    public function manageReply($action, $id = null)
    {
        if (!$id || !in_array($action, ['approve', 'delete', 'edit'])) {
            flash('dashboard_message', 'Invalid request', 'alert alert-danger');
            redirect('dashboard/community');
        }

        // Get reply to determine which topic to return to
        $reply = $this->forumModel->getReplyById($id);
        if (!$reply) {
            flash('dashboard_message', 'Reply not found', 'alert alert-danger');
            redirect('dashboard/community');
        }

        $success = false;
        $message = '';
        $redirectUrl = 'dashboard/community';

        switch ($action) {
            case 'approve':
                $success = $this->forumModel->updateReplyStatus($id, 'approved');
                $message = 'Reply approved successfully';
                break;
            case 'delete':
                $success = $this->forumModel->deleteReply($id);
                $message = 'Reply deleted successfully';
                break;
            case 'edit':
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['content'])) {
                    $content = trim($_POST['content']);
                    if (!empty($content)) {
                        $success = $this->forumModel->updateReply($id, $content);
                        $message = 'Reply updated successfully';
                    } else {
                        flash('dashboard_message', 'Reply content cannot be empty', 'alert alert-danger');
                        redirect('dashboard/community');
                    }
                } else {
                    // Show edit form
                    $data = [
                        'title' => 'Edit Reply',
                        'description' => 'Edit forum reply',
                        'active' => 'forums',
                        'active_parent' => 'community', // Added for proper sidebar highlighting
                        'reply' => $reply
                    ];
                    $this->view('dashboard/edit_reply', $data);
                    return;
                }
                break;
        }

        if ($success) {
            flash('dashboard_message', $message, 'alert alert-success');
            $this->dashboardService->logActivity('action', 'reply_' . $action, ['reply_id' => $id]);
        } else {
            flash('dashboard_message', 'Failed to ' . $action . ' reply', 'alert alert-danger');
        }

        redirect($redirectUrl);
    }

    public function createCategory()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name']) && isset($_POST['description'])) {
            $name = trim($_POST['name']);
            $description = trim($_POST['description']);

            if (empty($name)) {
                flash('dashboard_message', 'Category name is required', 'alert alert-danger');
                redirect('dashboard/community');
            }

            $data = [
                'name' => $name,
                'description' => $description
            ];

            if ($this->forumModel->createCategory($data)) {
                flash('dashboard_message', 'Category created successfully', 'alert alert-success');
                $this->dashboardService->logActivity('create', 'category', ['name' => $name]);
            } else {
                flash('dashboard_message', 'Failed to create category', 'alert alert-danger');
            }
        }

        redirect('dashboard/community');
    }

    public function manageCategory($action, $id = null)
    {
        if (!$id || !in_array($action, ['edit', 'delete'])) {
            flash('dashboard_message', 'Invalid request', 'alert alert-danger');
            redirect('dashboard/community');
        }

        if ($action === 'delete') {
            if ($this->forumModel->deleteCategory($id)) {
                flash('dashboard_message', 'Category deleted successfully', 'alert alert-success');
                $this->dashboardService->logActivity('delete', 'category', ['category_id' => $id]);
            } else {
                flash('dashboard_message', 'Failed to delete category', 'alert alert-danger');
            }
            redirect('dashboard/community');
        } elseif ($action === 'edit') {
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['name']) && isset($_POST['description'])) {
                $name = trim($_POST['name']);
                $description = trim($_POST['description']);

                if (empty($name)) {
                    flash('dashboard_message', 'Category name is required', 'alert alert-danger');
                    redirect('dashboard/community');
                }

                $data = [
                    'id' => $id,
                    'name' => $name,
                    'description' => $description
                ];

                if ($this->forumModel->updateCategory($data)) {
                    flash('dashboard_message', 'Category updated successfully', 'alert alert-success');
                    $this->dashboardService->logActivity('update', 'category', ['category_id' => $id]);
                } else {
                    flash('dashboard_message', 'Failed to update category', 'alert alert-danger');
                }
                redirect('dashboard/community');
            } else {
                $category = $this->forumModel->getCategoryById($id);
                $data = [
                    'title' => 'Edit Category',
                    'description' => 'Edit forum category',
                    'active' => 'community',
                    'category' => $category
                ];
                $this->view('dashboard/edit_category', $data);
                return;
            }
        }
    }

    public function updateCategory($id = null)
    {
        // Check if ID is set and request method is POST
        if (!$id || $_SERVER['REQUEST_METHOD'] !== 'POST') {
            flash('dashboard_message', 'Invalid request', 'alert alert-danger');
            redirect('dashboard/community?section=forums');
        }

        // Get the existing category to verify it exists
        $existingCategory = $this->forumModel->getCategoryById($id);
        if (!$existingCategory) {
            flash('dashboard_message', 'Category not found', 'alert alert-danger');
            redirect('dashboard/community?section=forums');
        }

        // Sanitize POST data
        $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_SPECIAL_CHARS);

        // Prepare data
        $data = [
            'id' => $id,
            'name' => trim($_POST['name']),
            'description' => trim($_POST['description']),
            'icon' => isset($_POST['icon']) ? trim($_POST['icon']) : null,
            'color' => isset($_POST['color']) ? trim($_POST['color']) : null,
            'display_order' => isset($_POST['display_order']) ? (int)$_POST['display_order'] : 0,
            'name_err' => '',
            'description_err' => ''
        ];

        // Handle permissions
        $data['perm_all_view'] = isset($_POST['perm_all_view']) ? 1 : 0;
        $data['perm_all_create'] = isset($_POST['perm_all_create']) ? 1 : 0;
        $data['perm_all_reply'] = isset($_POST['perm_all_reply']) ? 1 : 0;

        // Validate name - required field
        if (empty($data['name'])) {
            $data['name_err'] = 'Category name is required';
        } elseif (strlen($data['name']) > 50) {
            $data['name_err'] = 'Category name cannot exceed 50 characters';
        }

        // Validate description (optional but with length check)
        if (!empty($data['description']) && strlen($data['description']) > 255) {
            $data['description_err'] = 'Description cannot exceed 255 characters';
        }

        // Check if there are any errors
        if (!empty($data['name_err']) || !empty($data['description_err'])) {
            // Return to the edit form with errors
            $data['category'] = $existingCategory;
            $data['title'] = 'Edit Category';
            $data['description'] = 'Edit forum category';
            $data['active'] = 'forums';
            $data['active_parent'] = 'community';
            
            $this->view('dashboard/edit_category', $data);
            return;
        }

        // Update category
        if ($this->forumModel->updateCategory($data)) {
            // Log this update
            $this->dashboardService->logActivity('update', 'category', [
                'category_id' => $id,
                'name' => $data['name']
            ]);
            
            flash('dashboard_message', 'Category updated successfully', 'alert alert-success');
        } else {
            flash('dashboard_message', 'Failed to update category. Please try again.', 'alert alert-danger');
        }

        redirect('dashboard/community?section=forums');
    }

    public function reportsAction($action, $id = null)
    {
        if (!$id || !in_array($action, ['dismiss', 'delete'])) {
            flash('dashboard_message', 'Invalid request', 'alert alert-danger');
            redirect('dashboard/community');
        }

        $report = $this->forumModel->getReportById($id);
        if (!$report) {
            flash('dashboard_message', 'Report not found', 'alert alert-danger');
            redirect('dashboard/community');
        }

        if ($action === 'dismiss') {
            if ($this->forumModel->dismissReport($id)) {
                flash('dashboard_message', 'Report dismissed successfully', 'alert alert-success');
                $this->dashboardService->logActivity('action', 'dismiss_report', ['report_id' => $id]);
            } else {
                flash('dashboard_message', 'Failed to dismiss report', 'alert alert-danger');
            }
        } elseif ($action === 'delete') {
            // This will delete both the report and the reported content
            if ($report->content_type === 'topic') {
                $success = $this->forumModel->deleteTopic($report->content_id);
            } else {
                $success = $this->forumModel->deleteReply($report->content_id);
            }

            if ($success) {
                $this->forumModel->dismissReport($id);
                flash('dashboard_message', 'Content deleted and report resolved successfully', 'alert alert-success');
                $this->dashboardService->logActivity(
                    'action',
                    'delete_reported_content',
                    ['report_id' => $id, 'content_type' => $report->content_type]
                );
            } else {
                flash('dashboard_message', 'Failed to delete reported content', 'alert alert-danger');
            }
        }

        redirect('dashboard/community');
    }

    // API methods for AJAX operations

    // Get user data for AJAX requests
    public function getUserData()
    {
        // Check if user_id is provided in the request
        $userId = isset($_GET['user_id']) ? $_GET['user_id'] : null;

        if (!$userId) {
            http_response_code(400); // Bad Request
            echo json_encode(['success' => false, 'message' => 'Missing user ID']);
            return;
        }

        // Get user from database
        $user = $this->dashboardModel->getUserById($userId);

        if ($user) {
            // Return user data as JSON
            header('Content-Type: application/json');
            echo json_encode($user);
        } else {
            http_response_code(404); // Not Found
            echo json_encode(['success' => false, 'message' => 'User not found']);
        }
    }

    // Toggle user status
    public function toggleUserStatus()
    {
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); // Method Not Allowed
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        // Get user ID and new status from POST data
        $userId = isset($_POST['userId']) ? $_POST['userId'] : null;
        $newStatus = isset($_POST['status']) ? $_POST['status'] : null;

        if (!$userId || !$newStatus) {
            http_response_code(400); // Bad Request
            echo json_encode(['success' => false, 'message' => 'Missing required parameters']);
            return;
        }

        // Update user status in database
        $success = $this->dashboardModel->updateUserStatus($userId, $newStatus);

        if ($success) {
            // Return success response with content type header
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'User status updated successfully']);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['success' => false, 'message' => 'Failed to update user status']);
        }
    }

    // Delete user
    public function deleteUser()
    {
        // Check if request is POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405); // Method Not Allowed
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            return;
        }

        // Get user ID from POST data
        $userId = isset($_POST['userId']) ? $_POST['userId'] : null;

        if (!$userId) {
            http_response_code(400); // Bad Request
            echo json_encode(['success' => false, 'message' => 'Missing user ID']);
            return;
        }

        // Delete user from database
        $success = $this->dashboardModel->deleteUser($userId);

        if ($success) {
            echo json_encode(['success' => true, 'message' => 'User deleted successfully']);
        } else {
            http_response_code(500); // Internal Server Error
            echo json_encode(['success' => false, 'message' => 'Failed to delete user']);
        }
    }

    // Toggle sidebar state in session
    public function toggleSidebar()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['isOpen'])) {
            $_SESSION['sidebar_open'] = $_POST['isOpen'] == 1;
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    }
}
