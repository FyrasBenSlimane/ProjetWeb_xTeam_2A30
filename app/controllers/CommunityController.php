<?php

/**
 * CommunityController class
 * Handles all community related features including forums, groups, and resources
 */
class CommunityController extends Controller
{
    private $forumModel;
    private $groupModel;
    private $resourceModel;
    private $userModel;

    /**
     * Constructor
     */
    public function __construct()
    {
        // Initialize models
        $this->forumModel = $this->model('Forum');
        $this->groupModel = $this->model('Group');
        $this->resourceModel = $this->model('Resource');
        $this->userModel = $this->model('User');
    }

    /**
     * Default method - redirect to forums
     */
    public function index()
    {
        redirect('community/forums');
    }

    /**
     * FORUM RELATED METHODS
     */

    /**
     * Forums main page
     */
    public function forums()
    {
        // Get forum categories and recent topics
        $categories = $this->forumModel->getCategories();
        $recentTopics = $this->forumModel->getRecentTopics(8);

        $data = [
            'title' => 'Community Forums',
            'categories' => $categories,
            'recentTopics' => $recentTopics
        ];

        $this->view('users/community/forums/index', $data);
    }

    /**
     * Display topics in a specific category
     * @param string $slug Category slug
     */
    public function forumCategory($slug = '')
    {
        // Check if category exists
        $category = $this->forumModel->getCategoryBySlug($slug);

        if (!$category) {
            flash('topic_message', 'Category not found', 'alert alert-danger');
            redirect('community/forums');
        }

        // Get topics
        $topics = $this->forumModel->getTopicsByCategory($category->id);

        $data = [
            'title' => $category->name . ' - Forums',
            'category' => $category,
            'topics' => $topics
        ];

        $this->view('users/community/forums/category', $data);
    }

    /**
     * Display a specific topic
     * @param string $slug Topic slug
     */
    public function topic($slug = '')
    {
        // Check if topic exists
        $topic = $this->forumModel->getTopicBySlug($slug);

        if (!$topic) {
            flash('topic_message', 'Topic not found', 'alert alert-danger');
            redirect('community/forums');
        }

        // Increment view count
        $this->forumModel->incrementViewCount($topic->id);

        // Get topic author
        $author = $this->userModel->getUserById($topic->user_id);

        // Add author information to topic object
        if ($author) {
            $topic->author_joined = $author->created_at; // Add the author's join date
            $topic->author_posts = $this->forumModel->countUserPosts($author->id); // Optional: count user's posts
        }

        // Get replies
        $replies = $this->forumModel->getRepliesByTopicId($topic->id);

        $data = [
            'title' => $topic->title . ' - Forums',
            'topic' => $topic,
            'author' => $author,
            'replies' => $replies
        ];

        $this->view('users/community/forums/topic', $data);
    }

    /**
     * Create new topic form
     */
    public function createTopic()
    {
        // Check if user is logged in
        if (!isLoggedIn()) {
            flash('topic_message', 'You must be logged in to create topics', 'alert alert-danger');
            redirect('users/login');
        }

        // Check if form is submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // Process form
            $data = [
                'title' => trim($_POST['title']),
                'title_topic' => trim($_POST['title']),
                'category_id' => $_POST['category_id'],
                'content' => trim($_POST['content']),
                'user_id' => $_SESSION['user_id'],
                'title_err' => '',
                'category_err' => '',
                'content_err' => '',
                'categories' => $this->forumModel->getCategories()
            ];

            // Validate title
            if (empty($data['title_topic'])) {
                $data['title_err'] = 'Please enter a title';
            } elseif (strlen($data['title_topic']) < 5) {
                $data['title_err'] = 'Title must be at least 5 characters';
            } elseif (strlen($data['title_topic']) > 100) {
                $data['title_err'] = 'Title cannot exceed 100 characters';
            }

            // Validate category
            if (empty($data['category_id'])) {
                $data['category_err'] = 'Please select a category';
            }

            // Validate content
            if (empty($data['content'])) {
                $data['content_err'] = 'Please enter content for your topic';
            } elseif (strlen($data['content']) < 20) {
                $data['content_err'] = 'Content must be at least 20 characters';
            }

            // Check if there are no errors
            if (empty($data['title_err']) && empty($data['category_err']) && empty($data['content_err'])) {
                // Create topic
                if ($this->forumModel->createTopic($data)) {
                    flash('topic_message', 'Topic created successfully', 'alert alert-success');
                    redirect('community/forums');
                } else {
                    flash('topic_message', 'Something went wrong', 'alert alert-danger');
                    $this->view('users/community/forums/create_topic', $data);
                }
            } else {
                // Load view with errors
                $this->view('users/community/forums/create_topic', $data);
            }
        } else {
            // Load create topic form
            $data = [
                'title' => 'Create New Topic',
                'title_topic' => '',
                'category_id' => '',
                'content' => '',
                'categories' => $this->forumModel->getCategories()
            ];

            $this->view('users/community/forums/create_topic', $data);
        }
    }

    /**
     * Add reply to a topic
     * @param int $topicId Topic ID
     */
    public function addReply($topicId = 0)
    {
        // Check if user is logged in
        if (!isLoggedIn()) {
            flash('reply_message', 'You must be logged in to reply', 'alert alert-danger');
            redirect('users/login');
        }

        // Check if topic exists
        $topic = $this->forumModel->getTopicById($topicId);

        if (!$topic) {
            flash('reply_message', 'Topic not found', 'alert alert-danger');
            redirect('community/forums');
        }

        // Check if topic is closed
        if ($topic->status == 'closed') {
            flash('reply_message', 'This topic is closed and no longer accepts replies', 'alert alert-warning');
            redirect('community/topic/' . $topic->slug);
        }

        // Check if form is submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // Process form
            $data = [
                'topic_id' => $topicId,
                'user_id' => $_SESSION['user_id'],
                'content' => trim($_POST['content']),
                'content_err' => ''
            ];

            // Validate content
            if (empty($data['content'])) {
                $data['content_err'] = 'Reply cannot be empty';
            }

            // Check if there are no errors
            if (empty($data['content_err'])) {
                // Add reply
                if ($this->forumModel->createReply($data)) {
                    flash('reply_message', 'Reply added successfully', 'alert alert-success');
                    redirect('community/topic/' . $topic->slug);
                } else {
                    flash('reply_message', 'Something went wrong', 'alert alert-danger');
                    redirect('community/topic/' . $topic->slug);
                }
            } else {
                // Redirect back with error
                flash('reply_message', $data['content_err'], 'alert alert-danger');
                redirect('community/topic/' . $topic->slug);
            }
        } else {
            // Redirect to topic page
            redirect('community/topic/' . $topic->slug);
        }
    }

    /**
     * Mark reply as solution
     * @param int $replyId Reply ID
     */
    public function markAsSolution($replyId = 0)
    {
        // Check if user is logged in
        if (!isLoggedIn()) {
            flash('reply_message', 'You must be logged in', 'alert alert-danger');
            redirect('users/login');
        }

        // Get reply
        $reply = $this->forumModel->getReplyById($replyId);

        if (!$reply) {
            flash('reply_message', 'Reply not found', 'alert alert-danger');
            redirect('community/forums');
        }

        // Get topic
        $topic = $this->forumModel->getTopicById($reply->topic_id);

        // Check if user is the topic author
        if ($_SESSION['user_id'] != $topic->user_id && !isAdmin()) {
            flash('reply_message', 'You are not authorized to mark solutions', 'alert alert-danger');
            redirect('community/topic/' . $topic->slug);
        }

        // Mark as solution
        if ($this->forumModel->markAsSolution($replyId, $reply->topic_id)) {
            flash('reply_message', 'Reply marked as solution', 'alert alert-success');
        } else {
            flash('reply_message', 'Something went wrong', 'alert alert-danger');
        }

        redirect('community/topic/' . $topic->slug);
    }

    /**
     * Topic management functions
     */

    /**
     * Close a topic
     * @param int $topicId Topic ID
     */
    public function closeTopic($topicId = 0)
    {
        // Check if user is logged in
        if (!isLoggedIn()) {
            flash('topic_message', 'You must be logged in', 'alert alert-danger');
            redirect('users/login');
        }

        // Get topic
        $topic = $this->forumModel->getTopicById($topicId);

        if (!$topic) {
            flash('topic_message', 'Topic not found', 'alert alert-danger');
            redirect('community/forums');
        }

        // Check if user is the topic author or admin
        if ($_SESSION['user_id'] != $topic->user_id && !isAdmin()) {
            flash('topic_message', 'You are not authorized to close this topic', 'alert alert-danger');
            redirect('community/topic/' . $topic->slug);
        }

        // Close topic
        if ($this->forumModel->updateTopicStatus($topicId, 'closed')) {
            flash('topic_message', 'Topic closed successfully', 'alert alert-success');
        } else {
            flash('topic_message', 'Something went wrong', 'alert alert-danger');
        }

        redirect('community/topic/' . $topic->slug);
    }

    /**
     * Reopen a topic
     * @param int $topicId Topic ID
     */
    public function openTopic($topicId = 0)
    {
        // Check if user is logged in
        if (!isLoggedIn()) {
            flash('topic_message', 'You must be logged in', 'alert alert-danger');
            redirect('users/login');
        }

        // Get topic
        $topic = $this->forumModel->getTopicById($topicId);

        if (!$topic) {
            flash('topic_message', 'Topic not found', 'alert alert-danger');
            redirect('community/forums');
        }

        // Check if user is the topic author or admin
        if ($_SESSION['user_id'] != $topic->user_id && !isAdmin()) {
            flash('topic_message', 'You are not authorized to reopen this topic', 'alert alert-danger');
            redirect('community/topic/' . $topic->slug);
        }

        // Reopen topic
        if ($this->forumModel->updateTopicStatus($topicId, 'open')) {
            flash('topic_message', 'Topic reopened successfully', 'alert alert-success');
        } else {
            flash('topic_message', 'Something went wrong', 'alert alert-danger');
        }

        redirect('community/topic/' . $topic->slug);
    }

    /**
     * Pin a topic
     * @param int $topicId Topic ID
     */
    public function pinTopic($topicId = 0)
    {
        // Check if user is logged in
        if (!isLoggedIn()) {
            flash('topic_message', 'You must be logged in', 'alert alert-danger');
            redirect('users/login');
        }

        // Get topic
        $topic = $this->forumModel->getTopicById($topicId);

        if (!$topic) {
            flash('topic_message', 'Topic not found', 'alert alert-danger');
            redirect('community/forums');
        }

        // Check if user is admin
        if (!isAdmin()) {
            flash('topic_message', 'You are not authorized to pin topics', 'alert alert-danger');
            redirect('community/topic/' . $topic->slug);
        }

        // Pin topic
        if ($this->forumModel->updateTopicStatus($topicId, 'pinned')) {
            flash('topic_message', 'Topic pinned successfully', 'alert alert-success');
        } else {
            flash('topic_message', 'Something went wrong', 'alert alert-danger');
        }

        redirect('community/topic/' . $topic->slug);
    }

    /**
     * Unpin a topic
     * @param int $topicId Topic ID
     */
    public function unpinTopic($topicId = 0)
    {
        // Check if user is logged in
        if (!isLoggedIn()) {
            flash('topic_message', 'You must be logged in', 'alert alert-danger');
            redirect('users/login');
        }

        // Get topic
        $topic = $this->forumModel->getTopicById($topicId);

        if (!$topic) {
            flash('topic_message', 'Topic not found', 'alert alert-danger');
            redirect('community/forums');
        }

        // Check if user is admin
        if (!isAdmin()) {
            flash('topic_message', 'You are not authorized to unpin topics', 'alert alert-danger');
            redirect('community/topic/' . $topic->slug);
        }

        // Unpin topic
        if ($this->forumModel->updateTopicStatus($topicId, 'open')) {
            flash('topic_message', 'Topic unpinned successfully', 'alert alert-success');
        } else {
            flash('topic_message', 'Something went wrong', 'alert alert-danger');
        }

        redirect('community/topic/' . $topic->slug);
    }

    /**
     * Delete a topic
     * @param int $id Topic ID
     */
    public function deleteTopic($id = 0)
    {
        // Check if user is logged in
        if (!isLoggedIn()) {
            flash('topic_message', 'You must be logged in to delete topics', 'alert alert-danger');
            redirect('users/login');
        }

        // Get the topic
        $topic = $this->forumModel->getTopicById($id);

        // Check if topic exists
        if (!$topic) {
            flash('topic_message', 'Topic not found', 'alert alert-danger');
            redirect('community/forums');
        }

        // Check if user is authorized to delete (topic owner or admin)
        if ($_SESSION['user_id'] != $topic->user_id && !isAdmin()) {
            flash('topic_message', 'You are not authorized to delete this topic', 'alert alert-danger');
            redirect('community/topic/' . $topic->slug);
        }

        // Delete the topic
        if ($this->forumModel->deleteTopic($id)) {
            flash('topic_message', 'Topic deleted successfully', 'alert alert-success');
            redirect('community/forums');
        } else {
            flash('topic_message', 'Error deleting topic', 'alert alert-danger');
            redirect('community/topic/' . $topic->slug);
        }
    }

    /**
     * Delete a reply
     * @param int $replyId Reply ID
     */
    public function deleteReply($replyId = 0)
    {
        // Check if user is logged in
        if (!isLoggedIn()) {
            flash('reply_message', 'You must be logged in', 'alert alert-danger');
            redirect('users/login');
        }

        // Get reply
        $reply = $this->forumModel->getReplyById($replyId);

        if (!$reply) {
            flash('reply_message', 'Reply not found', 'alert alert-danger');
            redirect('community/forums');
        }

        // Get topic
        $topic = $this->forumModel->getTopicById($reply->topic_id);

        // Check if user is the reply author, topic author, or admin
        if ($_SESSION['user_id'] != $reply->user_id && $_SESSION['user_id'] != $topic->user_id && !isAdmin()) {
            flash('reply_message', 'You are not authorized to delete this reply', 'alert alert-danger');
            redirect('community/topic/' . $topic->slug);
        }

        // Delete reply
        if ($this->forumModel->deleteReply($replyId)) {
            flash('reply_message', 'Reply deleted successfully', 'alert alert-success');
        } else {
            flash('reply_message', 'Something went wrong', 'alert alert-danger');
        }

        redirect('community/topic/' . $topic->slug);
    }

    /**
     * Search topics in forums
     */
    public function searchTopics()
    {
        // Check if search query is set
        if (!isset($_GET['search']) || empty(trim($_GET['search']))) {
            redirect('community/forums');
        }

        $search = trim($_GET['search']);
        $sort = $_GET['sort'] ?? 'recent';
        $filter = $_GET['filter'] ?? 'all';

        // Get search results
        $topics = $this->forumModel->searchTopics($search, $sort, $filter);

        // Get categories for filter
        $categories = $this->forumModel->getCategories();

        $data = [
            'title' => 'Search Results: ' . $search,
            'topics' => $topics,
            'search' => $search,
            'sort' => $sort,
            'filter' => $filter,
            'categories' => $categories
        ];

        $this->view('users/community/forums/search', $data);
    }

    /**
     * View all topics (paginated)
     */
    public function allTopics()
    {
        // Get pagination parameters
        $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $limit = 20; // Topics per page
        $offset = ($page - 1) * $limit;

        // Get sort parameter
        $sort = $_GET['sort'] ?? 'recent';

        // Get all topics with pagination
        $topics = $this->forumModel->getAllTopics($limit, $offset, $sort);
        $totalTopics = $this->forumModel->countAllTopics();

        // Calculate total pages
        $totalPages = ceil($totalTopics / $limit);

        $data = [
            'title' => 'All Forum Topics',
            'topics' => $topics,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'sort' => $sort
        ];

        $this->view('users/community/forums/all_topics', $data);
    }

    /**
     * Edit a topic
     * @param int $topicId Topic ID
     */
    public function editTopic($topicId = 0)
    {
        // Check if user is logged in
        if (!isLoggedIn()) {
            flash('topic_message', 'You must be logged in', 'alert alert-danger');
            redirect('users/login');
        }

        // Get topic
        $topic = $this->forumModel->getTopicById($topicId);

        if (!$topic) {
            flash('topic_message', 'Topic not found', 'alert alert-danger');
            redirect('community/forums');
        }

        // Check if user is the topic author or admin
        if ($_SESSION['user_id'] != $topic->user_id && !isAdmin()) {
            flash('topic_message', 'You are not authorized to edit this topic', 'alert alert-danger');
            redirect('community/topic/' . $topic->slug);
        }

        // Check if form is submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_FULL_SPECIAL_CHARS);

            // Process form
            $data = [
                'id' => $topicId,
                'title' => trim($_POST['title']),
                'category_id' => $_POST['category_id'],
                'content' => trim($_POST['content']),
                'title_err' => '',
                'category_err' => '',
                'content_err' => '',
                'topic' => $topic,
                'categories' => $this->forumModel->getCategories()
            ];

            // Validate title
            if (empty($data['title'])) {
                $data['title_err'] = 'Please enter a title';
            } elseif (strlen($data['title']) < 5) {
                $data['title_err'] = 'Title must be at least 5 characters';
            } elseif (strlen($data['title']) > 100) {
                $data['title_err'] = 'Title cannot exceed 100 characters';
            }

            // Validate category
            if (empty($data['category_id'])) {
                $data['category_err'] = 'Please select a category';
            }

            // Validate content
            if (empty($data['content'])) {
                $data['content_err'] = 'Please enter content for your topic';
            } elseif (strlen($data['content']) < 20) {
                $data['content_err'] = 'Content must be at least 20 characters';
            }

            // Check if there are no errors
            if (empty($data['title_err']) && empty($data['category_err']) && empty($data['content_err'])) {
                // Update topic
                $updateData = [
                    'id' => $topicId,
                    'title' => $data['title'],
                    'category_id' => $data['category_id'],
                    'content' => $data['content']
                ];

                if ($this->forumModel->updateTopic($updateData)) {
                    flash('topic_message', 'Topic updated successfully', 'alert alert-success');
                    redirect('community/topic/' . $topic->slug);
                } else {
                    flash('topic_message', 'Something went wrong', 'alert alert-danger');
                    $this->view('users/community/forums/edit_topic', $data);
                }
            } else {
                // Load view with errors
                $this->view('users/community/forums/edit_topic', $data);
            }
        } else {
            // Load edit topic form
            $data = [
                'id' => $topicId,
                'title' => $topic->title,
                'category_id' => $topic->category_id,
                'content' => $topic->content,
                'topic' => $topic,
                'categories' => $this->forumModel->getCategories()
            ];

            $this->view('users/community/forums/edit_topic', $data);
        }
    }

    /**
     * Edit a reply
     * @param int $replyId Reply ID
     */
    public function editReply($replyId = 0)
    {
        // Check if request is AJAX
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&
            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        // Check if user is logged in
        if (!isLoggedIn()) {
            if ($isAjax) {
                echo json_encode(['success' => false, 'message' => 'You must be logged in to edit replies']);
                return;
            }
            flash('reply_message', 'You must be logged in to edit replies', 'alert alert-danger');
            redirect('users/login');
        }

        // Get reply
        $reply = $this->forumModel->getReplyById($replyId);

        if (!$reply) {
            if ($isAjax) {
                echo json_encode(['success' => false, 'message' => 'Reply not found']);
                return;
            }
            flash('reply_message', 'Reply not found', 'alert alert-danger');
            redirect('community/forums');
        }

        // Get topic
        $topic = $this->forumModel->getTopicById($reply->topic_id);

        // Check if user is the reply author or admin
        if ($_SESSION['user_id'] != $reply->user_id && !isAdmin()) {
            if ($isAjax) {
                echo json_encode(['success' => false, 'message' => 'You are not authorized to edit this reply']);
                return;
            }
            flash('reply_message', 'You are not authorized to edit this reply', 'alert alert-danger');
            redirect('community/topic/' . $topic->slug);
        }

        // For AJAX requests
        if ($isAjax) {
            // Get content from POST data (for AJAX request using JSON)
            $json = file_get_contents('php://input');
            $data = json_decode($json);

            if (json_last_error() !== JSON_ERROR_NONE) {
                // Fallback to regular POST
                $content = $_POST['content'] ?? '';
            } else {
                $content = $data->content ?? '';
            }

            // Validate content
            if (empty($content)) {
                echo json_encode(['success' => false, 'message' => 'Reply content cannot be empty']);
                return;
            }

            if (strlen($content) < 10) {
                echo json_encode(['success' => false, 'message' => 'Reply must be at least 10 characters']);
                return;
            }

            // Update reply
            if ($this->forumModel->updateReply($replyId, $content)) {
                // Format the content for display
                $formattedContent = nl2br(htmlspecialchars($content));

                echo json_encode([
                    'success' => true,
                    'message' => 'Reply updated successfully',
                    'content' => $content,
                    'formattedContent' => $formattedContent
                ]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Error updating reply']);
            }
            return;
        }

        // For regular form submission
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            $content = trim($_POST['content']);

            // Validate content
            if (empty($content)) {
                flash('reply_message', 'Reply content cannot be empty', 'alert alert-danger');
                redirect('community/topic/' . $topic->slug);
            }

            if (strlen($content) < 10) {
                flash('reply_message', 'Reply must be at least 10 characters', 'alert alert-danger');
                redirect('community/topic/' . $topic->slug);
            }

            // Update reply
            if ($this->forumModel->updateReply($replyId, $content)) {
                flash('reply_message', 'Reply updated successfully', 'alert alert-success');
            } else {
                flash('reply_message', 'Error updating reply', 'alert alert-danger');
            }

            redirect('community/topic/' . $topic->slug);
        } else {
            // Show edit form
            $data = [
                'title' => 'Edit Reply',
                'reply' => $reply,
                'topic' => $topic
            ];

            $this->view('dashboard/edit_reply', $data);
        }
    }

    /**
     * GROUPS RELATED METHODS
     */

    /**
     * Groups main page
     */
    public function groups()
    {
        // Get featured and recent groups
        $featuredGroups = $this->groupModel->getFeaturedGroups(3);
        $recentGroups = $this->groupModel->getRecentGroups(8);

        $data = [
            'title' => 'Community Groups',
            'featuredGroups' => $featuredGroups,
            'recentGroups' => $recentGroups
        ];

        $this->view('users/community/groups/index', $data);
    }

    /**
     * Display a specific group
     * @param string $slug Group slug
     */
    public function group($slug = '')
    {
        // Check if group exists
        $group = $this->groupModel->getGroupBySlug($slug);

        if (!$group) {
            flash('group_message', 'Group not found', 'alert alert-danger');
            redirect('community/groups');
        }

        // Check if group is private and user is not a member
        $isUserMember = false;
        $isUserLoggedIn = isLoggedIn();

        if ($isUserLoggedIn) {
            $isUserMember = $this->groupModel->isUserMember($_SESSION['user_id'], $group->id);
        }

        if (
            $group->is_private && !$isUserMember &&
            ($isUserLoggedIn && $group->creator_id != $_SESSION['user_id'] && !isAdmin())
        ) {
            flash('group_message', 'This group is private. You need to join to view its content.', 'alert alert-warning');
            redirect('community/groups');
        }

        // Get group members
        $members = $this->groupModel->getGroupMembers($group->id);

        // Get group posts if user is member or has proper permissions
        $posts = [];
        if (
            !$group->is_private || $isUserMember ||
            ($isUserLoggedIn && ($group->creator_id == $_SESSION['user_id'] || isAdmin()))
        ) {
            $posts = $this->groupModel->getGroupPosts($group->id);
        }

        $data = [
            'title' => $group->name . ' - Community Group',
            'group' => $group,
            'members' => $members,
            'posts' => $posts,
            'isUserMember' => $isUserMember
        ];

        $this->view('users/community/groups/view', $data);
    }

    /**
     * Create new group form
     */
    public function createGroup()
    {
        // Check if user is logged in
        if (!isLoggedIn()) {
            flash('group_message', 'You must be logged in to create groups', 'alert alert-danger');
            redirect('users/login');
        }

        // Check if form is submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            $data = [
                'title' => 'Create New Group',
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'is_private' => isset($_POST['is_private']) ? true : false,
                'user_id' => $_SESSION['user_id'],
                'name_err' => '',
                'description_err' => '',
                'cover_image_err' => ''
            ];

            // Validate name
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter a group name';
            } elseif (strlen($data['name']) < 3) {
                $data['name_err'] = 'Group name must be at least 3 characters';
            }

            // Validate description
            if (empty($data['description'])) {
                $data['description_err'] = 'Please enter a description';
            }

            // Handle file upload
            $coverImage = '';
            if (!empty($_FILES['cover_image']['name'])) {
                $fileUploader = new FileUpload();
                $uploadResult = $fileUploader->upload($_FILES['cover_image'], 'group_covers');

                if ($uploadResult['success']) {
                    $coverImage = $uploadResult['filename'];
                } else {
                    $data['cover_image_err'] = $uploadResult['error'];
                }
            }

            // Check if there are no errors
            if (empty($data['name_err']) && empty($data['description_err']) && empty($data['cover_image_err'])) {
                // Create group
                $groupData = [
                    'name' => $data['name'],
                    'description' => $data['description'],
                    'is_private' => $data['is_private'] ? 1 : 0,
                    'user_id' => $data['user_id'],
                    'cover_image' => $coverImage
                ];

                if ($this->groupModel->createGroup($groupData)) {
                    flash('group_message', 'Group created successfully', 'alert alert-success');
                    redirect('community/groups');
                } else {
                    flash('group_message', 'Something went wrong', 'alert alert-danger');
                    $this->view('users/community/groups/create', $data);
                }
            } else {
                // Load view with errors
                $this->view('users/community/groups/create', $data);
            }
        } else {
            // Load create group form
            $data = [
                'title' => 'Create New Group',
                'name' => '',
                'description' => '',
                'is_private' => false
            ];

            $this->view('users/community/groups/create', $data);
        }
    }

    /**
     * Join a group
     * @param int $groupId Group ID
     */
    public function joinGroup($groupId = 0)
    {
        // Check if user is logged in
        if (!isLoggedIn()) {
            flash('group_message', 'You must be logged in to join groups', 'alert alert-danger');
            redirect('users/login');
        }

        // Check if group exists
        $group = $this->groupModel->getGroupById($groupId);

        if (!$group) {
            flash('group_message', 'Group not found', 'alert alert-danger');
            redirect('community/groups');
        }

        // Check if user is already member
        if ($this->groupModel->isUserMember($_SESSION['user_id'], $groupId)) {
            flash('group_message', 'You are already a member of this group', 'alert alert-warning');
            redirect('community/group/' . $group->slug);
        }

        // Join group
        if ($group->is_private) {
            // For private groups, create a join request
            if ($this->groupModel->createJoinRequest($_SESSION['user_id'], $groupId)) {
                flash('group_message', 'Join request sent successfully. Waiting for admin approval.', 'alert alert-success');
            } else {
                flash('group_message', 'Something went wrong', 'alert alert-danger');
            }
        } else {
            // For public groups, join directly
            if ($this->groupModel->addMember($_SESSION['user_id'], $groupId)) {
                flash('group_message', 'Joined group successfully', 'alert alert-success');
            } else {
                flash('group_message', 'Something went wrong', 'alert alert-danger');
            }
        }

        redirect('community/group/' . $group->slug);
    }

    /**
     * Leave a group
     * @param int $groupId Group ID
     */
    public function leaveGroup($groupId = 0)
    {
        // Check if user is logged in
        if (!isLoggedIn()) {
            flash('group_message', 'You must be logged in', 'alert alert-danger');
            redirect('users/login');
        }

        // Check if group exists
        $group = $this->groupModel->getGroupById($groupId);

        if (!$group) {
            flash('group_message', 'Group not found', 'alert alert-danger');
            redirect('community/groups');
        }

        // Check if user is the group creator
        if ($group->creator_id == $_SESSION['user_id']) {
            flash('group_message', 'As the creator, you cannot leave the group. You can delete it instead.', 'alert alert-warning');
            redirect('community/group/' . $group->slug);
        }

        // Leave group
        if ($this->groupModel->removeMember($_SESSION['user_id'], $groupId)) {
            flash('group_message', 'Left group successfully', 'alert alert-success');
            redirect('community/groups');
        } else {
            flash('group_message', 'Something went wrong', 'alert alert-danger');
            redirect('community/group/' . $group->slug);
        }
    }

    /**
     * Add post to a group
     * @param int $groupId Group ID
     */
    public function addPost($groupId = 0)
    {
        // Check if user is logged in
        if (!isLoggedIn()) {
            flash('post_message', 'You must be logged in to post', 'alert alert-danger');
            redirect('users/login');
        }

        // Check if group exists
        $group = $this->groupModel->getGroupById($groupId);

        if (!$group) {
            flash('post_message', 'Group not found', 'alert alert-danger');
            redirect('community/groups');
        }

        // Check if user is member
        if (!$this->groupModel->isUserMember($_SESSION['user_id'], $groupId) && $group->creator_id != $_SESSION['user_id'] && !isAdmin()) {
            flash('post_message', 'You must be a member to post in this group', 'alert alert-warning');
            redirect('community/group/' . $group->slug);
        }

        // Check if form is submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            $data = [
                'content' => trim($_POST['content']),
                'user_id' => $_SESSION['user_id'],
                'group_id' => $groupId,
                'content_err' => ''
            ];

            // Validate content
            if (empty($data['content'])) {
                $data['content_err'] = 'Post content cannot be empty';
                flash('post_message', $data['content_err'], 'alert alert-danger');
                redirect('community/group/' . $group->slug);
            }

            // Handle file upload
            $attachment = '';
            if (!empty($_FILES['attachment']['name'])) {
                $fileUploader = new FileUpload();

                // Validate file type and size before upload
                $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                $maxSize = 5 * 1024 * 1024; // 5MB

                if (!in_array($_FILES['attachment']['type'], $allowedTypes)) {
                    flash('post_message', 'Invalid file type. Only images, PDFs, and Word documents are allowed.', 'alert alert-danger');
                    redirect('community/group/' . $group->slug);
                }

                if ($_FILES['attachment']['size'] > $maxSize) {
                    flash('post_message', 'File is too large. Maximum file size is 5MB.', 'alert alert-danger');
                    redirect('community/group/' . $group->slug);
                }

                $uploadResult = $fileUploader->upload($_FILES['attachment'], 'group_attachments');

                if ($uploadResult['success']) {
                    $attachment = $uploadResult['filename'];
                } else {
                    flash('post_message', 'File upload error: ' . $uploadResult['error'], 'alert alert-danger');
                    redirect('community/group/' . $group->slug);
                }
            }

            // Add attachment to data
            $data['attachment'] = $attachment;

            // Create post
            if ($this->groupModel->createPost($data)) {
                flash('post_message', 'Post created successfully', 'alert alert-success');
            } else {
                flash('post_message', 'Something went wrong', 'alert alert-danger');
            }

            redirect('community/group/' . $group->slug);
        } else {
            redirect('community/group/' . $group->slug);
        }
    }

    /**
     * Delete a post
     * @param int $postId Post ID
     */
    public function deletePost($postId = 0)
    {
        // Check if user is logged in
        if (!isLoggedIn()) {
            flash('post_message', 'You must be logged in', 'alert alert-danger');
            redirect('users/login');
        }

        // Get post
        $post = $this->groupModel->getPostById($postId);

        if (!$post) {
            flash('post_message', 'Post not found', 'alert alert-danger');
            redirect('community/groups');
        }

        // Get group
        $group = $this->groupModel->getGroupById($post->group_id);

        // Check if user is post author, group creator, or admin
        if ($_SESSION['user_id'] != $post->user_id && $_SESSION['user_id'] != $group->creator_id && !isAdmin()) {
            flash('post_message', 'You are not authorized to delete this post', 'alert alert-danger');
            redirect('community/group/' . $group->slug);
        }

        // Delete post
        if ($this->groupModel->deletePost($postId)) {
            flash('post_message', 'Post deleted successfully', 'alert alert-success');
        } else {
            flash('post_message', 'Something went wrong', 'alert alert-danger');
        }

        redirect('community/group/' . $group->slug);
    }

    /**
     * Group admin methods
     */

    /**
     * List and approve pending join requests
     * @param int $groupId Group ID
     */
    public function pendingRequests($groupId = 0)
    {
        // Check if user is logged in
        if (!isLoggedIn()) {
            flash('group_message', 'You must be logged in', 'alert alert-danger');
            redirect('users/login');
        }

        // Check if group exists
        $group = $this->groupModel->getGroupById($groupId);

        if (!$group) {
            flash('group_message', 'Group not found', 'alert alert-danger');
            redirect('community/groups');
        }

        // Check if user is group creator or admin
        if ($_SESSION['user_id'] != $group->creator_id && !isAdmin()) {
            flash('group_message', 'You are not authorized to view join requests', 'alert alert-danger');
            redirect('community/group/' . $group->slug);
        }

        // Get pending requests
        $requests = $this->groupModel->getJoinRequests($groupId);

        $data = [
            'title' => 'Pending Join Requests - ' . $group->name,
            'group' => $group,
            'requests' => $requests
        ];

        $this->view('users/community/groups/pending_requests', $data);
    }

    /**
     * RESOURCES RELATED METHODS
     */

    /**
     * Resources main page
     */
    public function resources()
    {
        // Get categories, featured resources and recent resources
        $categories = $this->resourceModel->getCategories();
        $featuredResources = $this->resourceModel->getFeaturedResources(3);
        $recentResources = $this->resourceModel->getRecentResources(8);

        $data = [
            'title' => 'Community Resources',
            'categories' => $categories,
            'featuredResources' => $featuredResources,
            'recentResources' => $recentResources
        ];

        $this->view('users/community/resources/index', $data);
    }

    /**
     * Display resources in a specific category
     * @param int $categoryId Category ID
     */
    public function resourceCategory($categoryId = 0)
    {
        // Check if category exists
        $category = $this->resourceModel->getCategoryById($categoryId);

        if (!$category) {
            flash('resource_message', 'Category not found', 'alert alert-danger');
            redirect('community/resources');
        }

        // Get resources in category
        $resources = $this->resourceModel->getResourcesByCategory($categoryId);

        $data = [
            'title' => $category->name . ' - Resources',
            'category' => $category,
            'resources' => $resources
        ];

        $this->view('users/community/resources/category', $data);
    }

    /**
     * Display a specific resource
     * @param int $id Resource ID
     */
    public function resource($id = 0)
    {
        // Check if resource exists
        $resource = $this->resourceModel->getResourceById($id);

        if (!$resource) {
            flash('resource_message', 'Resource not found', 'alert alert-danger');
            redirect('community/resources');
        }

        // Increment view count
        $this->resourceModel->incrementViewCount($id);

        // Get resource details
        $author = $this->userModel->getUserById($resource->user_id);
        $ratings = $this->resourceModel->getRatings($id);
        $tags = $this->resourceModel->getResourceTags($id);

        $data = [
            'title' => $resource->title . ' - Resources',
            'resource' => $resource,
            'author' => $author,
            'ratings' => $ratings,
            'tags' => $tags
        ];

        $this->view('users/community/resources/view', $data);
    }

    /**
     * Create new resource form
     */
    public function createResource()
    {
        // Check if user is logged in
        if (!isLoggedIn()) {
            flash('resource_message', 'You must be logged in to share resources', 'alert alert-danger');
            redirect('users/login');
        }

        // Check if form is submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            $data = [
                'title' => trim($_POST['title']),
                'category_id' => $_POST['category_id'],
                'resource_type' => $_POST['resource_type'] ?? 'article',
                'description' => trim($_POST['description']),
                'content' => trim($_POST['content'] ?? ''),
                'external_link' => trim($_POST['external_link'] ?? ''),
                'user_id' => $_SESSION['user_id'],
                'title_err' => '',
                'category_err' => '',
                'description_err' => '',
                'file_err' => '',
                'thumbnail_err' => '',
                'categories' => $this->resourceModel->getCategories(),
                'tags' => $this->resourceModel->getTags(),
                'selected_tags' => $_POST['tags'] ?? []
            ];

            // Validate title
            if (empty($data['title'])) {
                $data['title_err'] = 'Please enter a title';
            }

            // Validate category
            if (empty($data['category_id'])) {
                $data['category_err'] = 'Please select a category';
            }

            // Validate description
            if (empty($data['description'])) {
                $data['description_err'] = 'Please enter a description';
            }

            // Handle file uploads
            $file = '';
            $thumbnail = '';

            // Resource file
            if (!empty($_FILES['file']['name'])) {
                $fileUploader = new FileUpload();
                $uploadResult = $fileUploader->upload($_FILES['file'], 'resource_files');

                if ($uploadResult['success']) {
                    $file = $uploadResult['filename'];
                } else {
                    $data['file_err'] = $uploadResult['error'];
                }
            }

            // Thumbnail
            if (!empty($_FILES['thumbnail']['name'])) {
                $fileUploader = new FileUpload();
                $uploadResult = $fileUploader->upload($_FILES['thumbnail'], 'resource_thumbnails');

                if ($uploadResult['success']) {
                    $thumbnail = $uploadResult['filename'];
                } else {
                    $data['thumbnail_err'] = $uploadResult['error'];
                }
            }

            // Check if there are no errors
            if (
                empty($data['title_err']) && empty($data['category_err']) &&
                empty($data['description_err']) && empty($data['file_err']) &&
                empty($data['thumbnail_err'])
            ) {

                // Create resource
                $resourceData = [
                    'title' => $data['title'],
                    'category_id' => $data['category_id'],
                    'resource_type' => $data['resource_type'],
                    'description' => $data['description'],
                    'content' => $data['content'],
                    'external_link' => $data['external_link'],
                    'file_path' => $file,
                    'thumbnail' => $thumbnail,
                    'user_id' => $data['user_id'],
                    'tags' => $data['selected_tags']
                ];

                if ($resourceId = $this->resourceModel->createResource($resourceData)) {
                    flash('resource_message', 'Resource shared successfully', 'alert alert-success');
                    redirect('community/resource/' . $resourceId);
                } else {
                    flash('resource_message', 'Something went wrong', 'alert alert-danger');
                    $this->view('users/community/resources/create', $data);
                }
            } else {
                // Load view with errors
                $this->view('users/community/resources/create', $data);
            }
        } else {
            // Load create resource form
            $data = [
                'title' => '',
                'category_id' => '',
                'resource_type' => 'article',
                'description' => '',
                'content' => '',
                'external_link' => '',
                'categories' => $this->resourceModel->getCategories(),
                'tags' => $this->resourceModel->getTags(),
                'selected_tags' => []
            ];

            $this->view('users/community/resources/create', $data);
        }
    }

    /**
     * Download a resource file
     * @param int $id Resource ID
     */
    public function downloadResource($id = 0)
    {
        // Check if resource exists
        $resource = $this->resourceModel->getResourceById($id);

        if (!$resource || empty($resource->file_path)) {
            flash('resource_message', 'File not available', 'alert alert-danger');
            redirect('community/resource/' . $id);
        }

        // Increment download count
        $this->resourceModel->incrementDownloadCount($id);

        // Get file path
        $filePath = APPROOT . '/../public/uploads/resource_files/' . $resource->file_path;

        if (file_exists($filePath)) {
            header('Content-Description: File Transfer');
            header('Content-Type: application/octet-stream');
            header('Content-Disposition: attachment; filename="' . basename($resource->file_path) . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate');
            header('Pragma: public');
            header('Content-Length: ' . filesize($filePath));
            readfile($filePath);
            exit;
        } else {
            flash('resource_message', 'File not found', 'alert alert-danger');
            redirect('community/resource/' . $id);
        }
    }

    /**
     * Rate a resource
     * @param int $id Resource ID
     */
    public function rateResource($id = 0)
    {
        // Check if user is logged in
        if (!isLoggedIn()) {
            flash('rating_message', 'You must be logged in to rate resources', 'alert alert-danger');
            redirect('users/login');
        }

        // Check if resource exists
        $resource = $this->resourceModel->getResourceById($id);

        if (!$resource) {
            flash('rating_message', 'Resource not found', 'alert alert-danger');
            redirect('community/resources');
        }

        // Check if user has already rated
        if ($this->resourceModel->hasUserRated($_SESSION['user_id'], $id)) {
            flash('rating_message', 'You have already rated this resource', 'alert alert-warning');
            redirect('community/resource/' . $id);
        }

        // Check if form is submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            $data = [
                'user_id' => $_SESSION['user_id'],
                'resource_id' => $id,
                'rating' => $_POST['rating'],
                'comment' => trim($_POST['comment'] ?? '')
            ];

            // Add rating
            if ($this->resourceModel->addRating($data)) {
                flash('rating_message', 'Thank you for rating this resource', 'alert alert-success');
            } else {
                flash('rating_message', 'Something went wrong', 'alert alert-danger');
            }

            redirect('community/resource/' . $id);
        } else {
            redirect('community/resource/' . $id);
        }
    }

    /**
     * Search resources
     */
    public function searchResources()
    {
        // Check if search query is set
        if (isset($_GET['search'])) {
            $search = trim($_GET['search']);
            $sort = $_GET['sort'] ?? 'newest';

            // Perform search
            $resources = $this->resourceModel->searchResources($search, $sort);

            $data = [
                'title' => 'Search Results - Resources',
                'resources' => $resources,
                'search' => $search,
                'sort' => $sort
            ];

            $this->view('users/community/resources/search', $data);
        } else {
            redirect('community/resources');
        }
    }

    /**
     * Search groups
     */
    public function searchGroups()
    {
        // Check if search query is set
        if (isset($_GET['search'])) {
            $search = trim($_GET['search']);
            $sort = $_GET['sort'] ?? 'newest';

            // Perform search
            $groups = $this->groupModel->searchGroups($search, $sort);

            $data = [
                'title' => 'Search Results - Groups',
                'groups' => $groups,
                'search' => $search,
                'sort' => $sort
            ];

            $this->view('users/community/groups/search', $data);
        } else {
            redirect('community/groups');
        }
    }
}
