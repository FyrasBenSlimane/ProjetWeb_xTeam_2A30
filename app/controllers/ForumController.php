<?php
class ForumController extends Controller {
    private $forumModel;
    private $userModel;
    
    public function __construct() {
        $this->forumModel = $this->model('Forum');
        $this->userModel = $this->model('User');
    }
    
    /**
     * Forum index/home page
     */
    public function index() {
        // Get categories
        $categories = $this->forumModel->getCategories();
        
        // Get featured threads
        $featuredThreads = $this->forumModel->getFeaturedThreads(2);
        
        // Get recent threads, excluding featured ones
        $featuredIds = array_map(function($thread) {
            return $thread->id;
        }, $featuredThreads);
        
        $recentThreads = $this->forumModel->getRecentThreads(4, $featuredIds);
        
        // Check if user is logged in
        $isLoggedIn = isLoggedIn();
        
        $data = [
            'title' => 'Community Forum',
            'description' => 'Join discussions, ask questions, and share knowledge with other professionals.',
            'categories' => $categories,
            'featuredThreads' => $featuredThreads,
            'recentThreads' => $recentThreads,
            'is_logged_in' => $isLoggedIn
        ];
        
        $this->view('layouts/header', $data);
        $this->view('community/forum', $data);
        $this->view('layouts/footer');
    }
    
    /**
     * View threads in a specific category
     * 
     * @param string $slug Category slug
     */
    public function category($slug) {
        // Get category
        $category = $this->forumModel->getCategoryBySlug($slug);
        
        if (!$category) {
            redirect('forum');
        }
        
        // Get page number
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page);
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        // Get threads in category
        $threads = $this->forumModel->getThreadsByCategory($category->id, $limit, $offset);
        
        // Get total thread count for pagination
        $threadCount = $this->forumModel->countThreadsByCategory($category->id);
        $totalPages = ceil($threadCount / $limit);
        
        // Check if user is logged in
        $isLoggedIn = isLoggedIn();
        
        $data = [
            'title' => $category->name . ' - Forum',
            'description' => $category->description,
            'category' => $category,
            'threads' => $threads,
            'thread_count' => $threadCount,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'is_logged_in' => $isLoggedIn
        ];
        
        $this->view('layouts/header', $data);
        $this->view('community/forum_category', $data);
        $this->view('layouts/footer');
    }
    
    /**
     * View a single thread
     * 
     * @param string $slug Thread slug
     */
    public function thread($slug) {
        // Check if this is a JSON request
        $isJsonRequest = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                        strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest' || 
                        (isset($_GET['format']) && $_GET['format'] === 'json');
        
        // Get thread
        $thread = $this->forumModel->getThreadBySlug($slug);
        
        if (!$thread) {
            if ($isJsonRequest) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Thread not found']);
                return;
            }
            redirect('forum');
        }
        
        // Increment view count only for non-JSON requests
        if (!$isJsonRequest) {
            $this->forumModel->incrementViewCount($thread->id);
        }
        
        // Get page number
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page);
        $limit = 20;
        $offset = ($page - 1) * $limit;
        
        // Get replies
        $replies = $this->forumModel->getRepliesByThread($thread->id, $limit, $offset);
        
        // Get total reply count for pagination
        $replyCount = $this->forumModel->countRepliesByThread($thread->id);
        $totalPages = ceil($replyCount / $limit);
        
        // Check if user is logged in
        $isLoggedIn = isLoggedIn();
        $userId = $isLoggedIn ? $_SESSION['user_id'] : 0;
        
        // Get user votes and set is_own_reply for each reply
        if ($isLoggedIn) {
            $threadVote = $this->forumModel->getUserVote($userId, 'thread', $thread->id);
            
            foreach ($replies as &$reply) {
                $reply->user_vote = $this->forumModel->getUserVote($userId, 'reply', $reply->id);
                
                // Add is_own_reply property to indicate if the current user owns this reply
                $reply->is_own_reply = ($reply->user_id == $userId);
            }
        } else {
            $threadVote = null;
            
            // Set is_own_reply to false for all replies when user is not logged in
            foreach ($replies as &$reply) {
                $reply->is_own_reply = false;
            }
        }
        
        // Prepare response data
        $responseData = [
            'success' => true,
            'thread' => $thread,
            'replies' => $replies,
            'reply_count' => $replyCount,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'is_logged_in' => $isLoggedIn,
            'thread_vote' => $threadVote
        ];
        
        // If this is a JSON request, return JSON
        if ($isJsonRequest) {
            try {
                header('Content-Type: application/json');
                echo json_encode($responseData);
                return;
            } catch (Exception $e) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false, 
                    'message' => 'Error encoding JSON response', 
                    'error' => $e->getMessage()
                ]);
                return;
            }
        }
        
        // For regular page view
        $data = [
            'title' => $thread->title,
            'description' => 'Discussion thread in ' . $thread->category_name,
            'thread' => $thread,
            'replies' => $replies,
            'reply_count' => $replyCount,
            'current_page' => $page,
            'total_pages' => $totalPages,
            'is_logged_in' => $isLoggedIn,
            'thread_vote' => $threadVote
        ];
        
        try {
            $this->view('layouts/header', $data);
            $this->view('community/forum_thread', $data);
            $this->view('layouts/footer');
        } catch (Exception $e) {
            // If the view doesn't exist or other error
            if ($isJsonRequest) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false, 
                    'message' => 'Error rendering view: ' . $e->getMessage()
                ]);
            } else {
                // Fallback to a simple error message
                echo '<h1>Error</h1>';
                echo '<p>' . $e->getMessage() . '</p>';
                echo '<p><a href="' . URL_ROOT . '/forum">Return to Forum</a></p>';
            }
        }
    }
    
    /**
     * Search for threads
     */
    public function search() {
        // Check if query parameter exists
        if (!isset($_GET['q']) || empty($_GET['q'])) {
            redirect('forum');
        }
        
        $query = trim($_GET['q']);
        
        // Get page number
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $page = max(1, $page);
        $limit = 10;
        $offset = ($page - 1) * $limit;
        
        // Search threads
        $threads = $this->forumModel->searchThreads($query, $limit, $offset);
        
        // Check if user is logged in
        $isLoggedIn = isLoggedIn();
        
        $data = [
            'title' => 'Search: ' . htmlspecialchars($query),
            'description' => 'Forum search results for ' . htmlspecialchars($query),
            'query' => $query,
            'threads' => $threads,
            'is_logged_in' => $isLoggedIn
        ];
        
        $this->view('layouts/header', $data);
        $this->view('community/forum_search', $data);
        $this->view('layouts/footer');
    }
    
    /**
     * Create a new thread
     */
    public function create() {
        // Check if user is logged in
        if (!isLoggedIn()) {
            // If this is an AJAX request
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'You must be logged in to create a thread']);
                return;
            }
            
            $_SESSION['redirect_url'] = URL_ROOT . '/forum/create';
            redirect('users/login');
        }
        
        // Get categories for dropdown
        $categories = $this->forumModel->getCategories();
        
        // Check if form is submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $data = [
                'title' => trim($_POST['title']),
                'content' => trim($_POST['content']),
                'category_id' => (int)$_POST['category_id'],
                'user_id' => $_SESSION['user_id'],
                'title_err' => '',
                'content_err' => '',
                'category_id_err' => ''
            ];
            
            // Validate title
            if (empty($data['title'])) {
                $data['title_err'] = 'Please enter a title';
            } elseif (strlen($data['title']) < 10) {
                $data['title_err'] = 'Title must be at least 10 characters';
            }
            
            // Validate content
            if (empty($data['content'])) {
                $data['content_err'] = 'Please enter content';
            } elseif (strlen($data['content']) < 20) {
                $data['content_err'] = 'Content must be at least 20 characters';
            }
            
            // Validate category
            if (empty($data['category_id'])) {
                $data['category_id_err'] = 'Please select a category';
            } else {
                // Check if category exists
                $category = $this->forumModel->getCategoryById($data['category_id']);
                if (!$category) {
                    $data['category_id_err'] = 'Invalid category';
                }
            }
            
            // Make sure no errors
            if (empty($data['title_err']) && empty($data['content_err']) && empty($data['category_id_err'])) {
                // Create thread
                $threadId = $this->forumModel->createThread($data);
                
                if ($threadId) {
                    // Get the thread to get its slug
                    $thread = $this->forumModel->getThreadById($threadId);
                    
                    // If this is an AJAX request
                    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                        header('Content-Type: application/json');
                        echo json_encode([
                            'success' => true,
                            'message' => 'Thread created successfully',
                            'thread' => $thread
                        ]);
                        return;
                    }
                    
                    flash('thread_success', 'Your thread has been created');
                    redirect('forum/thread/' . $thread->slug);
                } else {
                    // If this is an AJAX request
                    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => false, 'message' => 'Failed to create thread']);
                        return;
                    }
                    
                    die('Something went wrong');
                }
            } else {
                // If this is an AJAX request
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => false,
                        'errors' => [
                            'title' => $data['title_err'],
                            'content' => $data['content_err'],
                            'category_id' => $data['category_id_err']
                        ]
                    ]);
                    return;
                }
                
                // Load view with errors
                $data['categories'] = $categories;
                $data['title'] = 'Create New Thread';
                $data['description'] = 'Start a new discussion thread';
                
                $this->view('layouts/header', $data);
                $this->view('community/forum_create', $data);
                $this->view('layouts/footer');
            }
        } else {
            // If this is an AJAX request
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Invalid request method']);
                return;
            }
            
            // Initialize empty data
            $data = [
                'title' => 'Create New Thread',
                'description' => 'Start a new discussion thread',
                'categories' => $categories,
                'title_err' => '',
                'content_err' => '',
                'category_id_err' => ''
            ];
            
            $this->view('layouts/header', $data);
            $this->view('community/forum_create', $data);
            $this->view('layouts/footer');
        }
    }
    
    /**
     * Add a reply to a thread
     * 
     * @param int $threadId Thread ID
     */
    public function reply($threadId) {
        // Check if user is logged in
        if (!isLoggedIn()) {
            // If this is an AJAX request
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'You must be logged in to reply to threads']);
                return;
            }
            
            flash('reply_error', 'You must be logged in to reply to threads', 'alert alert-danger');
            redirect('forum/thread/' . $threadId);
        }
        
        // Verify thread exists
        $thread = $this->forumModel->getThreadById($threadId);
        if (!$thread) {
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Thread not found']);
                return;
            }
            
            redirect('forum');
        }
        
        // Check if form is submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get content and trim it first
            $content = isset($_POST['content']) ? trim($_POST['content']) : '';
            
            $data = [
                'thread_id' => $threadId,
                'content' => $content,
                'user_id' => $_SESSION['user_id'],
                'content_err' => ''
            ];
            
            // Validate content
            if (empty($data['content'])) {
                $data['content_err'] = 'Please enter a reply';
            } elseif (strlen($data['content']) < 10) {
                $data['content_err'] = 'Reply must be at least 10 characters';
            }
            
            // Make sure no errors
            if (empty($data['content_err'])) {
                try {
                    // Create reply
                    $replyId = $this->forumModel->createReply($data);
                    
                    if ($replyId) {
                        // If this is an AJAX request
                        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                            // Get user information
                            $user = $this->userModel->getUserById($_SESSION['user_id']);
                            
                            // Create a properly structured reply object
                            $reply = new stdClass();
                            $reply->id = $replyId;
                            $reply->content = $data['content'];
                            $reply->created_at = date('Y-m-d H:i:s');
                            $reply->author_name = $user->name;
                            $reply->author_avatar = $user->profile_image;
                            $reply->is_solution = false;
                            
                            header('Content-Type: application/json');
                            echo json_encode([
                                'success' => true, 
                                'reply' => $reply, 
                                'message' => 'Reply added successfully',
                                'thread_id' => $threadId,
                                'debug_info' => [
                                    'post_data' => $_POST,
                                    'is_ajax' => isset($_SERVER['HTTP_X_REQUESTED_WITH']),
                                    'origin' => $_SERVER['HTTP_ORIGIN'] ?? 'unknown',
                                    'referer' => $_SERVER['HTTP_REFERER'] ?? 'unknown',
                                ]
                            ]);
                            return;
                        }
                        
                        flash('reply_success', 'Your reply has been added');
                        redirect('forum/thread/' . $thread->slug);
                    } else {
                        // If this is an AJAX request
                        if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                            header('Content-Type: application/json');
                            echo json_encode([
                                'success' => false, 
                                'message' => 'Failed to create reply',
                                'debug_info' => [
                                    'thread_id' => $threadId,
                                    'user_id' => $_SESSION['user_id'],
                                    'content_length' => strlen($data['content'])
                                ]
                            ]);
                            return;
                        }
                        
                        die('Something went wrong');
                    }
                } catch (Exception $e) {
                    // If this is an AJAX request
                    if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                        header('Content-Type: application/json');
                        echo json_encode([
                            'success' => false, 
                            'message' => 'Error creating reply: ' . $e->getMessage(),
                            'debug_info' => [
                                'error' => $e->getMessage(),
                                'file' => $e->getFile(),
                                'line' => $e->getLine()
                            ]
                        ]);
                        return;
                    }
                    
                    die('Error: ' . $e->getMessage());
                }
            } else {
                // If this is an AJAX request
                if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => $data['content_err']]);
                    return;
                }
                
                // Get the thread to redirect back to it with error
                $thread = $this->forumModel->getThreadById($threadId);
                flash('reply_error', $data['content_err'], 'alert alert-danger');
                redirect('forum/thread/' . $thread->slug);
            }
        } else {
            // If this is an AJAX request
            if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Invalid request method']);
                return;
            }
            
            // Redirect if not POST request
            redirect('forum');
        }
    }
    
    /**
     * Handle voting on threads and replies
     */
    public function vote() {
        // Check if user is logged in
        if (!isLoggedIn()) {
            header('Content-Type: application/json');
            echo json_encode(['success' => false, 'message' => 'You must be logged in to vote']);
            return;
        }
        
        // Check if AJAX request
        if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['content_type']) && isset($_POST['content_id']) && isset($_POST['vote_type'])) {
            $data = [
                'user_id' => $_SESSION['user_id'],
                'content_type' => $_POST['content_type'],
                'content_id' => (int)$_POST['content_id'],
                'vote_type' => $_POST['vote_type']
            ];
            
            // Validate content type
            if (!in_array($data['content_type'], ['thread', 'reply'])) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Invalid content type']);
                return;
            }
            
            // Validate vote type
            if (!in_array($data['vote_type'], ['up', 'down'])) {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'Invalid vote type']);
                return;
            }
            
            // Process vote
            $success = $this->forumModel->vote($data);
            
            // Return response
            header('Content-Type: application/json');
            if ($success) {
                // Get new vote counts
                if ($data['content_type'] == 'reply') {
                    $upvotes = $this->forumModel->getVoteCount($data['content_id'], 'reply', 'up');
                    $downvotes = $this->forumModel->getVoteCount($data['content_id'], 'reply', 'down');
                    echo json_encode([
                        'success' => true, 
                        'upvotes' => $upvotes,
                        'downvotes' => $downvotes
                    ]);
                } else {
                    echo json_encode(['success' => true]);
                }
            } else {
                echo json_encode(['success' => false, 'message' => 'Failed to process vote']);
            }
        } else {
            // Redirect if not AJAX request
            redirect('forum');
        }
    }
    
    /**
     * Mark a reply as the solution to a thread
     * 
     * @param int $replyId Reply ID
     * @param int $threadId Thread ID
     */
    public function markSolution($replyId, $threadId) {
        // Check if user is logged in
        if (!isLoggedIn()) {
            flash('solution_error', 'You must be logged in to mark a solution', 'alert alert-danger');
            redirect('forum/thread/' . $threadId);
        }
        
        // Get the thread
        $thread = $this->forumModel->getThreadById($threadId);
        
        // Check if thread exists and user is the author
        if (!$thread || $thread->user_id != $_SESSION['user_id']) {
            flash('solution_error', 'You can only mark solutions for your own threads', 'alert alert-danger');
            redirect('forum/thread/' . $thread->slug);
        }
        
        // Mark solution
        $success = $this->forumModel->markAsSolution($replyId, $threadId);
        
        if ($success) {
            flash('solution_success', 'Solution marked successfully');
        } else {
            flash('solution_error', 'Failed to mark solution', 'alert alert-danger');
        }
        
        redirect('forum/thread/' . $thread->slug);
    }
    
    /**
     * Edit a reply
     * 
     * @param int $replyId Reply ID
     */
    public function editReply($replyId) {
        // Start output buffering to capture any unwanted output
        ob_start();
        
        // Check for AJAX request
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                 strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        
        // Check if user is logged in
        if (!isLoggedIn()) {
            if ($isAjax) {
                // Clean any output that might have been generated
                ob_end_clean();
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'You must be logged in to edit replies']);
                return;
            }
            
            flash('reply_error', 'You must be logged in to edit replies', 'alert alert-danger');
            redirect('forum');
        }
        
        try {
            // Get the reply from the database
            $reply = $this->forumModel->getReplyById($replyId);
            
            // Check if reply exists
            if (!$reply) {
                if ($isAjax) {
                    ob_end_clean();
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Reply not found']);
                    return;
                }
                
                flash('reply_error', 'Reply not found', 'alert alert-danger');
                redirect('forum');
            }
            
            // Check if user is the author of the reply
            if ($reply->user_id != $_SESSION['user_id']) {
                if ($isAjax) {
                    ob_end_clean();
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'You can only edit your own replies']);
                    return;
                }
                
                flash('reply_error', 'You can only edit your own replies', 'alert alert-danger');
                redirect('forum');
            }
            
            // Check if form is submitted
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Get content and trim it first
                $content = isset($_POST['content']) ? trim($_POST['content']) : '';
                
                $data = [
                    'id' => $replyId,
                    'content' => $content,
                    'content_err' => ''
                ];
                
                // Validate content
                if (empty($data['content'])) {
                    $data['content_err'] = 'Please enter a reply';
                } elseif (strlen($data['content']) < 10) {
                    $data['content_err'] = 'Reply must be at least 10 characters';
                }
                
                // Make sure no errors
                if (empty($data['content_err'])) {
                    // Update reply
                    $success = $this->forumModel->updateReply($data);
                    
                    if ($success) {
                        // Get the updated reply
                        $updatedReply = $this->forumModel->getReplyById($replyId);
                        
                        // If this is an AJAX request
                        if ($isAjax) {
                            ob_end_clean();
                            header('Content-Type: application/json');
                            echo json_encode([
                                'success' => true, 
                                'message' => 'Reply updated successfully',
                                'reply' => $updatedReply
                            ]);
                            return;
                        }
                        
                        // Get the thread to redirect back to it
                        $thread = $this->forumModel->getThreadById($reply->thread_id);
                        flash('reply_success', 'Your reply has been updated');
                        redirect('forum/thread/' . $thread->slug);
                    } else {
                        // If this is an AJAX request
                        if ($isAjax) {
                            ob_end_clean();
                            header('Content-Type: application/json');
                            echo json_encode(['success' => false, 'message' => 'Failed to update reply']);
                            return;
                        }
                        
                        // Get the thread to redirect back to it with error
                        $thread = $this->forumModel->getThreadById($reply->thread_id);
                        flash('reply_error', 'Failed to update reply', 'alert alert-danger');
                        redirect('forum/thread/' . $thread->slug);
                    }
                } else {
                    // If this is an AJAX request
                    if ($isAjax) {
                        ob_end_clean();
                        header('Content-Type: application/json');
                        echo json_encode(['success' => false, 'message' => $data['content_err']]);
                        return;
                    }
                    
                    // Get the thread to redirect back to it with error
                    $thread = $this->forumModel->getThreadById($reply->thread_id);
                    flash('reply_error', $data['content_err'], 'alert alert-danger');
                    redirect('forum/thread/' . $thread->slug);
                }
            } else {
                // If this is an AJAX request
                if ($isAjax) {
                    ob_end_clean();
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
                    return;
                }
                
                // Redirect if not POST request
                redirect('forum');
            }
        } catch (Exception $e) {
            if ($isAjax) {
                ob_end_clean();
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false, 
                    'message' => 'Error processing request: ' . $e->getMessage()
                ]);
                return;
            }
            
            // Log the error
            error_log('Error in editReply: ' . $e->getMessage());
            flash('reply_error', 'An unexpected error occurred', 'alert alert-danger');
            redirect('forum');
        }
        
        // Clean any buffered output if we reach here
        ob_end_clean();
    }
    
    /**
     * Delete a reply
     * 
     * @param int $replyId Reply ID
     */
    public function deleteReply($replyId) {
        // Start output buffering to capture any unwanted output
        ob_start();
        
        // Check for AJAX request
        $isAjax = isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                 strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        
        try {
            // Check if user is logged in
            if (!isLoggedIn()) {
                if ($isAjax) {
                    ob_end_clean();
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'You must be logged in to delete replies']);
                    return;
                }
                
                flash('reply_error', 'You must be logged in to delete replies', 'alert alert-danger');
                redirect('forum');
            }
            
            // Get the reply from the database
            $reply = $this->forumModel->getReplyById($replyId);
            
            // Check if reply exists
            if (!$reply) {
                if ($isAjax) {
                    ob_end_clean();
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Reply not found']);
                    return;
                }
                
                flash('reply_error', 'Reply not found', 'alert alert-danger');
                redirect('forum');
            }
            
            // Check if user is the author of the reply
            if ($reply->user_id != $_SESSION['user_id']) {
                if ($isAjax) {
                    ob_end_clean();
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'You can only delete your own replies']);
                    return;
                }
                
                flash('reply_error', 'You can only delete your own replies', 'alert alert-danger');
                redirect('forum');
            }
            
            // Check if form is submitted (POST)
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                // Delete reply
                $success = $this->forumModel->deleteReply($replyId);
                
                if ($success) {
                    // If this is an AJAX request
                    if ($isAjax) {
                        ob_end_clean();
                        header('Content-Type: application/json');
                        echo json_encode([
                            'success' => true, 
                            'message' => 'Reply deleted successfully'
                        ]);
                        return;
                    }
                    
                    // Get the thread to redirect back to it
                    $thread = $this->forumModel->getThreadById($reply->thread_id);
                    flash('reply_success', 'Your reply has been deleted');
                    redirect('forum/thread/' . $thread->slug);
                } else {
                    // If this is an AJAX request
                    if ($isAjax) {
                        ob_end_clean();
                        header('Content-Type: application/json');
                        echo json_encode(['success' => false, 'message' => 'Failed to delete reply']);
                        return;
                    }
                    
                    // Get the thread to redirect back to it with error
                    $thread = $this->forumModel->getThreadById($reply->thread_id);
                    flash('reply_error', 'Failed to delete reply', 'alert alert-danger');
                    redirect('forum/thread/' . $thread->slug);
                }
            } else {
                // If this is an AJAX request
                if ($isAjax) {
                    ob_end_clean();
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
                    return;
                }
                
                // Redirect if not POST request
                redirect('forum');
            }
        } catch (Exception $e) {
            if ($isAjax) {
                ob_end_clean();
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => false, 
                    'message' => 'Error processing request: ' . $e->getMessage()
                ]);
                return;
            }
            
            // Log the error
            error_log('Error in deleteReply: ' . $e->getMessage());
            flash('reply_error', 'An unexpected error occurred', 'alert alert-danger');
            redirect('forum');
        }
        
        // Clean any buffered output if we reach here
        ob_end_clean();
    }
} 