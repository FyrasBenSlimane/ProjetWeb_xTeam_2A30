<?php

/**
 * LearningController Class
 * Handles learning resources functionality
 */
class LearningController extends Controller
{
    public function __construct()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            redirect('users/auth?action=login');
        }
    }

    /**
     * Main resources page showing categorized educational content
     */
    public function resources()
    {
        $data = [
            'title' => 'Learning Resources',
            'description' => 'Improve your skills with our curated tutorials and resources',
            
            // Tech & Programming Resources
            'techResources' => [
                [
                    'title' => 'Modern JavaScript for Beginners',
                    'description' => 'Learn JavaScript from scratch with this comprehensive tutorial series',
                    'type' => 'video',
                    'duration' => '8 hours',
                    'level' => 'Beginner',
                    'url' => 'https://www.youtube.com/watch?v=hdI2bqOjy3c',
                    'thumbnail' => 'https://img.youtube.com/vi/hdI2bqOjy3c/hqdefault.jpg',
                    'provider' => 'Traversy Media',
                ],
                [
                    'title' => 'React JS Crash Course',
                    'description' => 'Learn the React JavaScript library in this comprehensive crash course',
                    'type' => 'video',
                    'duration' => '1 hour 48 min',
                    'level' => 'Intermediate',
                    'url' => 'https://www.youtube.com/watch?v=w7ejDZ8SWv8',
                    'thumbnail' => 'https://img.youtube.com/vi/w7ejDZ8SWv8/hqdefault.jpg',
                    'provider' => 'Traversy Media',
                ],
                [
                    'title' => 'Python for Data Science',
                    'description' => 'Learn how to use Python for data analysis and visualization',
                    'type' => 'document',
                    'duration' => '25 min read',
                    'level' => 'Intermediate',
                    'url' => 'https://www.dataquest.io/blog/python-data-science/',
                    'thumbnail' => 'https://source.unsplash.com/random/300x200/?python',
                    'provider' => 'Dataquest',
                ],
                [
                    'title' => 'Full Stack Development Roadmap',
                    'description' => 'A complete guide to becoming a full stack web developer',
                    'type' => 'document',
                    'duration' => '20 min read',
                    'level' => 'All Levels',
                    'url' => 'https://roadmap.sh/full-stack',
                    'thumbnail' => 'https://source.unsplash.com/random/300x200/?coding',
                    'provider' => 'roadmap.sh',
                ],
            ],
            
            // Design Resources
            'designResources' => [
                [
                    'title' => 'UI/UX Design Fundamentals',
                    'description' => 'Learn the core principles of user interface and user experience design',
                    'type' => 'video',
                    'duration' => '3 hours 20 min',
                    'level' => 'Beginner',
                    'url' => 'https://www.youtube.com/watch?v=c9Wg6Cb_YlU',
                    'thumbnail' => 'https://img.youtube.com/vi/c9Wg6Cb_YlU/hqdefault.jpg',
                    'provider' => 'Bring Your Own Laptop',
                ],
                [
                    'title' => 'Adobe Photoshop for Beginners',
                    'description' => 'Master the basics of Adobe Photoshop with this beginner-friendly tutorial',
                    'type' => 'video',
                    'duration' => '1 hour 30 min',
                    'level' => 'Beginner',
                    'url' => 'https://www.youtube.com/watch?v=IyR_uYsRdPs',
                    'thumbnail' => 'https://img.youtube.com/vi/IyR_uYsRdPs/hqdefault.jpg',
                    'provider' => 'Envato Tuts+',
                ],
                [
                    'title' => 'Color Theory for Designers',
                    'description' => 'Learn how to use color effectively in your design projects',
                    'type' => 'document',
                    'duration' => '15 min read',
                    'level' => 'Intermediate',
                    'url' => 'https://www.smashingmagazine.com/2010/01/color-theory-for-designers-part-1-the-meaning-of-color/',
                    'thumbnail' => 'https://source.unsplash.com/random/300x200/?color',
                    'provider' => 'Smashing Magazine',
                ],
                [
                    'title' => 'Typography Fundamentals',
                    'description' => 'Master the art of typography for more effective designs',
                    'type' => 'document',
                    'duration' => '18 min read',
                    'level' => 'All Levels',
                    'url' => 'https://designmodo.com/typography-basics/',
                    'thumbnail' => 'https://source.unsplash.com/random/300x200/?typography',
                    'provider' => 'Designmodo',
                ],
            ],
            
            // Business & Marketing Resources
            'businessResources' => [
                [
                    'title' => 'Digital Marketing Strategy',
                    'description' => 'Learn how to create an effective digital marketing strategy',
                    'type' => 'video',
                    'duration' => '2 hours 15 min',
                    'level' => 'Intermediate',
                    'url' => 'https://www.youtube.com/watch?v=hZysQbQXmQE',
                    'thumbnail' => 'https://img.youtube.com/vi/hZysQbQXmQE/hqdefault.jpg',
                    'provider' => 'Neil Patel',
                ],
                [
                    'title' => 'Freelance Business Essentials',
                    'description' => 'Everything you need to know to run a successful freelance business',
                    'type' => 'video',
                    'duration' => '1 hour 45 min',
                    'level' => 'Beginner',
                    'url' => 'https://www.youtube.com/watch?v=mICKivxwloc',
                    'thumbnail' => 'https://img.youtube.com/vi/mICKivxwloc/hqdefault.jpg',
                    'provider' => 'Chris Do',
                ],
                [
                    'title' => 'Client Communication Guide',
                    'description' => 'Learn effective strategies for client communication',
                    'type' => 'document',
                    'duration' => '12 min read',
                    'level' => 'All Levels',
                    'url' => 'https://www.shopify.com/partners/blog/client-communication',
                    'thumbnail' => 'https://source.unsplash.com/random/300x200/?meeting',
                    'provider' => 'Shopify Partners',
                ],
                [
                    'title' => 'Pricing Your Freelance Work',
                    'description' => 'How to set rates that reflect your value and expertise',
                    'type' => 'document',
                    'duration' => '15 min read',
                    'level' => 'Intermediate',
                    'url' => 'https://www.creativeboom.com/tips/how-to-price-yourself-as-a-freelancer/',
                    'thumbnail' => 'https://source.unsplash.com/random/300x200/?money',
                    'provider' => 'Creative Boom',
                ],
            ],
            
            // Career Development Resources
            'careerResources' => [
                [
                    'title' => 'Creating a Professional Portfolio',
                    'description' => 'Learn how to build a portfolio that attracts clients and showcases your best work',
                    'type' => 'video',
                    'duration' => '48 min',
                    'level' => 'All Levels',
                    'url' => 'https://www.youtube.com/watch?v=u-RLu_8kwA0',
                    'thumbnail' => 'https://img.youtube.com/vi/u-RLu_8kwA0/hqdefault.jpg',
                    'provider' => 'DesignCourse',
                ],
                [
                    'title' => 'Networking for Freelancers',
                    'description' => 'Effective networking strategies to find new clients and opportunities',
                    'type' => 'video',
                    'duration' => '1 hour 12 min',
                    'level' => 'Beginner',
                    'url' => 'https://www.youtube.com/watch?v=_3fo4kafO_0',
                    'thumbnail' => 'https://img.youtube.com/vi/_3fo4kafO_0/hqdefault.jpg',
                    'provider' => 'The Futur',
                ],
                [
                    'title' => 'Remote Work Best Practices',
                    'description' => 'Tips for staying productive and effective while working remotely',
                    'type' => 'document',
                    'duration' => '10 min read',
                    'level' => 'All Levels',
                    'url' => 'https://www.toptal.com/remote-work-playbook',
                    'thumbnail' => 'https://source.unsplash.com/random/300x200/?remote',
                    'provider' => 'Toptal',
                ],
                [
                    'title' => 'Building Your Personal Brand',
                    'description' => 'Strategies for developing a strong personal brand in your industry',
                    'type' => 'document',
                    'duration' => '14 min read',
                    'level' => 'Intermediate',
                    'url' => 'https://blog.hubspot.com/marketing/personal-branding',
                    'thumbnail' => 'https://source.unsplash.com/random/300x200/?branding',
                    'provider' => 'HubSpot',
                ],
            ],
        ];
        
        $this->view('learning/resources', $data);
    }
    
    /**
     * Filter resources by category
     */
    public function category($category = '')
    {
        // In a real application, you would fetch category-specific resources from a database
        redirect('learning/resources');
    }
    
    /**
     * Track resource clicks for analytics
     */
    public function track()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process resource click tracking
            // This would typically be stored in a database
            
            // Return success response for AJAX requests
            header('Content-Type: application/json');
            echo json_encode(['success' => true]);
            exit;
        }
        
        // Redirect if accessed directly
        redirect('learning/resources');
    }
}