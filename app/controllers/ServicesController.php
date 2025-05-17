<?php
class ServicesController extends Controller {
    private $serviceCategories;
    private $mockServices;
    private $userModel;
    private $jobModel;
    
    public function __construct() {
        // Initialize models
        $this->userModel = $this->model('User');
        $this->jobModel = $this->model('Job');
        
        // Get categories from jobs table
        $this->serviceCategories = $this->getUniqueCategories();
        
        // Initialize mock service data for now (to be replaced with DB data)
        $this->mockServices = $this->generateMockServices();
    }
    
    // New method to get unique categories from the jobs table
    private function getUniqueCategories() {
        $categories = $this->jobModel->getUniqueCategories();
        
        // Format categories as slug => name for consistency
        $formattedCategories = [];
        foreach ($categories as $category) {
            // Create slug from category name (lowercase, replace spaces with hyphens)
            $slug = strtolower(str_replace(' ', '-', $category));
            $formattedCategories[$slug] = $category;
        }
        
        // Add default categories if they don't exist in the database
        $defaultCategories = [
            'programming' => 'Programming',
            'design' => 'Design',
            'digital-marketing' => 'Digital Marketing',
            'writing' => 'Writing'
        ];
        
        foreach ($defaultCategories as $slug => $name) {
            if (!isset($formattedCategories[$slug])) {
                $formattedCategories[$slug] = $name;
            }
        }
        
        return $formattedCategories;
    }

    /**
     * Search for jobs and freelancers
     */
    public function search() {
        // Initialize search type - default to jobs
        $searchType = isset($_GET['type']) ? $_GET['type'] : 'jobs';
        
        // Initialize models
        $jobModel = $this->model('Job');
        $userModel = $this->model('User');
        
        // Get search query from URL
        $query = isset($_GET['q']) ? trim($_GET['q']) : '';
        
        // Default data structure
        $data = [
            'query' => $query,
            'resultCount' => 0,
            'results' => [],
            'page' => isset($_GET['page']) ? (int)$_GET['page'] : 1,
            'categories' => []
        ];
        
        // Set pagination variables
        $limit = 10;
        $offset = ($data['page'] - 1) * $limit;
        
        // Handle different search types
        if ($searchType === 'talent') {
            // Set up filters for searching freelancers
            $filters = [
                'limit' => $limit,
                'offset' => $offset
            ];
            
            // Add experience level filter if provided
            if (isset($_GET['experience_level']) && !empty($_GET['experience_level'])) {
                $filters['experience_level'] = $_GET['experience_level'];
                $data['experienceLevel'] = $_GET['experience_level'];
            }
            
            // Add country filter if provided
            if (isset($_GET['country']) && !empty($_GET['country'])) {
                $filters['country'] = $_GET['country'];
                $data['country'] = $_GET['country'];
            }
            
            // Add hourly rate range filters if provided
            if (isset($_GET['min_rate']) && is_numeric($_GET['min_rate'])) {
                $filters['min_rate'] = (float)$_GET['min_rate'];
                $data['minRate'] = $_GET['min_rate'];
            }
            
            if (isset($_GET['max_rate']) && is_numeric($_GET['max_rate'])) {
                $filters['max_rate'] = (float)$_GET['max_rate'];
                $data['maxRate'] = $_GET['max_rate'];
            }
            
            // Add sorting if provided
            if (isset($_GET['sort'])) {
                $data['sort'] = $_GET['sort'];
                
                switch ($_GET['sort']) {
                    case 'rate_high':
                        $filters['sort'] = 'rate_high';
                        break;
                    case 'rate_low':
                        $filters['sort'] = 'rate_low';
                        break;
                    default:
                        $filters['sort'] = 'relevance';
                        break;
                }
            }
            
            // Search for freelancers
            $data['results'] = $userModel->searchFreelancers($query, $filters);
            $data['resultCount'] = count($userModel->searchFreelancers($query, array_diff_key($filters, ['limit' => true, 'offset' => true])));
            
            // Load the talent search results view
            $this->view('services/talent_results', $data);
        } else {
            // Set up filters for searching jobs
            $filters = [
                'limit' => $limit,
                'offset' => $offset
            ];
            
            // Add category filter if provided
            if (isset($_GET['category']) && !empty($_GET['category'])) {
                $filters['category'] = $_GET['category'];
                $data['activeCategory'] = $_GET['category'];
            }
            
            // Add experience level filter if provided
            if (isset($_GET['experience_level']) && !empty($_GET['experience_level'])) {
                $filters['experience_level'] = $_GET['experience_level'];
                $data['experienceLevel'] = $_GET['experience_level'];
            }
            
            // Add job type filter if provided
            if (isset($_GET['job_type']) && !empty($_GET['job_type'])) {
                $filters['job_type'] = $_GET['job_type'];
                $data['jobType'] = $_GET['job_type'];
            }
            
            // Add budget range filters if provided
            if (isset($_GET['min_budget']) && is_numeric($_GET['min_budget'])) {
                $filters['min_budget'] = (float)$_GET['min_budget'];
                $data['minBudget'] = $_GET['min_budget'];
            }
            
            if (isset($_GET['max_budget']) && is_numeric($_GET['max_budget'])) {
                $filters['max_budget'] = (float)$_GET['max_budget'];
                $data['maxBudget'] = $_GET['max_budget'];
            }
            
            // Add sorting if provided
            if (isset($_GET['sort'])) {
                $data['sort'] = $_GET['sort'];
                $filters['sort'] = $_GET['sort'];
            }
            
            // Search for jobs
            $data['results'] = $jobModel->searchJobs($query, $filters);
            $data['resultCount'] = count($jobModel->searchJobs($query, array_diff_key($filters, ['limit' => true, 'offset' => true])));
            
            // Get categories for sidebar
            $categories = $jobModel->getUniqueCategories();
            
            // Convert categories to slug => name format for the view
            $categoriesMap = [];
            foreach ($categories as $category) {
                $slug = strtolower(str_replace(' ', '-', $category));
                $categoriesMap[$slug] = $category;
            }
            $data['categories'] = $categoriesMap;
            
            // Load the job search results view
            $this->view('services/job_results', $data);
        }
    }

    public function index() {
        redirect('services/browse');
    }
    
    // Browse all services with optional filtering
    public function browse() {
        // Get search parameters from URL
        $search = isset($_GET['search']) ? trim($_GET['search']) : '';
        $category = isset($_GET['category']) ? trim($_GET['category']) : '';
        $minPrice = isset($_GET['min_price']) ? (int)$_GET['min_price'] : 0;
        $maxPrice = isset($_GET['max_price']) ? (int)$_GET['max_price'] : 1000;
        $rating = isset($_GET['rating']) ? (int)$_GET['rating'] : 0;
        $sort = isset($_GET['sort']) ? trim($_GET['sort']) : 'popular';
        $expertMode = isset($_GET['expert']) && $_GET['expert'] == '1';
        
        // Filter services based on parameters
        $filteredServices = $this->filterServices($search, $category, $minPrice, $maxPrice, $rating);
        
        // Sort services
        $sortedServices = $this->sortServices($filteredServices, $sort);
        
        // Generate experts if expert mode is enabled
        $experts = [];
        if ($expertMode) {
            $experts = $this->generateExperts($category, count($sortedServices));
        }
        
        // Get the active category name if a category filter is applied
        $activeCategoryName = isset($this->serviceCategories[$category]) ? $this->serviceCategories[$category] : '';
        
        // Create page title based on mode
        $pageTitle = $expertMode 
            ? ($activeCategoryName ? $activeCategoryName . ' Experts' : 'Browse Experts')
            : ($activeCategoryName ? $activeCategoryName . ' Services' : 'Browse Services');
        
        // Create data array for the view
        $data = [
            'title' => $pageTitle,
            'description' => $expertMode ? 'Find expert consultants in your field' : 'Find the perfect service for your needs',
            'services' => $sortedServices,
            'experts' => $experts,
            'expertMode' => $expertMode,
            'categories' => $this->serviceCategories,
            'activeCategory' => $category,
            'search' => $search,
            'minPrice' => $minPrice,
            'maxPrice' => $maxPrice,
            'rating' => $rating,
            'sort' => $sort,
            'totalServices' => count($sortedServices),
            'totalExperts' => count($experts)
        ];
        
        // Load views
        $this->view('layouts/header', $data);
        $this->view('services/browse', $data);
        $this->view('layouts/footer');
    }
    
    // View a single service - RENAMED from view() to viewService()
    public function viewService($id = null) {
        if ($id === null) {
            redirect('services/browse');
        }
        
        // Find the service by ID
        $service = null;
        foreach ($this->mockServices as $mockService) {
            if ($mockService['id'] == $id) {
                $service = $mockService;
                break;
            }
        }
        
        // If service not found, redirect to browse page
        if ($service === null) {
            redirect('services/browse');
        }
        
        // Get related services in the same category
        $relatedServices = [];
        foreach ($this->mockServices as $mockService) {
            if ($mockService['category'] === $service['category'] && $mockService['id'] != $service['id']) {
                $relatedServices[] = $mockService;
                if (count($relatedServices) >= 4) break; // Limit to 4 related services
            }
        }
        
        $data = [
            'title' => $service['title'],
            'description' => substr($service['description'], 0, 160) . '...',
            'service' => $service,
            'relatedServices' => $relatedServices
        ];
        
        // Load views
        $this->view('layouts/header', $data);
        $this->view('services/view', $data);
        $this->view('layouts/footer');
    }
    
    // Create a new service (just display the form for now)
    public function create() {
        // Check if user is logged in
        if (!isLoggedIn()) {
            redirect('users/login');
        }
        
        $data = [
            'title' => 'Create a Service',
            'description' => 'Offer your expertise as a service',
            'categories' => $this->serviceCategories
        ];
        
        // Load views
        $this->view('layouts/header', $data);
        $this->view('services/create', $data);
        $this->view('layouts/footer');
    }
    
    // Helper function to generate mock services
    private function generateMockServices() {
        $services = [];
        $serviceDescriptions = [
            'programming' => [
                "I will create a professional website for your business using modern web technologies. Your website will be fully responsive, SEO optimized, and ready to attract customers.",
                "I'll develop custom software applications tailored to your business needs. Whether it's a management system, CRM, or any other software solution, I can build it for you.",
                "I can fix bugs and issues in your existing website or application. I'll analyze the problem, provide a solution, and ensure everything works smoothly.",
                "I will create mobile applications for iOS and Android platforms that will help your business reach more customers and provide better services.",
                "I'll optimize your database performance and structure to ensure fast and reliable data access. This will improve your application's performance significantly.",
                "I can integrate various APIs into your website or application to add new functionalities and improve user experience.",
                "I'll develop custom WordPress plugins to extend the functionality of your WordPress website according to your specific requirements."
            ],
            'design' => [
                "I will design a unique logo that represents your brand's identity and values. Your logo will be modern, memorable, and scalable for various applications.",
                "I'll create stunning graphic designs for your social media posts that will capture attention and increase engagement with your audience.",
                "I will design professional business cards, letterheads, and other stationery items that reflect your brand's identity and make a lasting impression.",
                "I'll design attractive and user-friendly UI/UX for your website or application to improve user experience and increase conversions.",
                "I will create custom illustrations for your website, blog, or social media to make your content more engaging and visually appealing.",
                "I'll design eye-catching banners, ads, and other marketing materials that will help promote your business effectively.",
                "I will design a professional and visually appealing presentation that will help you communicate your ideas effectively to your audience."
            ],
            'digital-marketing' => [
                "I will develop and execute a comprehensive digital marketing strategy to increase your online presence and drive more traffic to your website.",
                "I'll optimize your website for search engines to improve your rankings and visibility, which will bring more organic traffic and potential customers.",
                "I will manage your social media accounts to increase your followers, engagement, and brand awareness across various platforms.",
                "I'll create and manage effective Google Ads campaigns to drive targeted traffic to your website and increase conversions.",
                "I will analyze your website's performance and provide detailed reports with actionable insights to improve your online presence.",
                "I'll design and implement email marketing campaigns to nurture leads, retain customers, and increase sales for your business.",
                "I will help you build a strong brand identity that resonates with your target audience and sets you apart from competitors."
            ],
            'writing' => [
                "I will write engaging and informative blog posts for your website that will attract readers and improve your SEO rankings.",
                "I'll create compelling product descriptions that highlight the benefits and features of your products to increase sales.",
                "I will write professional press releases for your business announcements, product launches, or events to get media coverage.",
                "I'll translate your content from English to Spanish (or vice versa) while preserving the meaning and tone of the original text.",
                "I will proofread and edit your content to ensure it's free of grammatical errors, typos, and inconsistencies, making it more professional.",
                "I'll write persuasive sales copy for your website, landing pages, or emails that will convince visitors to take the desired action.",
                "I will craft a professional resume and cover letter that highlights your skills and experience to help you land your dream job."
            ],
            'video' => [
                "I will edit your raw footage into a professional video that tells your story effectively and keeps viewers engaged throughout.",
                "I'll create stunning motion graphics and animations that will make your videos more engaging and help explain complex concepts simply.",
                "I will produce a professional explainer video that clearly communicates your product's benefits and features to potential customers.",
                "I'll create captivating intros and outros for your YouTube videos to make them more professional and memorable for viewers.",
                "I will create eye-catching video ads for your social media campaigns that will grab attention and drive engagement with your brand.",
                "I'll edit and enhance your product photos to make them more attractive and professional for your online store or marketing materials.",
                "I will create a professional video testimonial that showcases customer satisfaction and builds trust with potential customers."
            ],
            'music' => [
                "I will compose original music for your videos, podcasts, or other projects that perfectly matches the mood and enhances the overall experience.",
                "I'll mix and master your music to professional standards, ensuring it sounds great on all devices and platforms.",
                "I will create a catchy jingle or audio logo for your brand that helps customers remember and recognize your business instantly.",
                "I'll provide professional voice-over services for your videos, commercials, or other content with the right tone and delivery.",
                "I will transcribe your audio or video content into text with high accuracy, making it more accessible and SEO-friendly.",
                "I'll produce a professional podcast episode, handling recording, editing, and mixing to ensure the highest audio quality.",
                "I will create sound effects and audio design for your videos, games, or applications to enhance the user experience."
            ],
            'business' => [
                "I will develop a comprehensive business plan for your startup or existing business to help you secure funding and guide your growth strategy.",
                "I'll provide expert financial analysis and advice to help you make informed decisions about investments, expenses, and revenue strategies.",
                "I will conduct market research to identify trends, opportunities, and threats in your industry to help you stay competitive and grow.",
                "I'll create and implement effective customer service strategies to improve satisfaction, retention, and overall experience with your brand.",
                "I will optimize your business processes to increase efficiency, reduce costs, and improve overall productivity across your organization.",
                "I'll develop a strategic marketing plan for your business that outlines specific actions to achieve your growth and revenue goals.",
                "I will provide virtual assistant services to handle administrative tasks, allowing you to focus on the core aspects of your business."
            ],
            'ai-services' => [
                "I will develop AI-powered chatbots for your website or social media that can engage with customers 24/7 and handle common inquiries.",
                "I'll implement machine learning algorithms to analyze your business data and provide valuable insights and predictions for better decision-making.",
                "I will create custom AI solutions tailored to your specific business needs, whether it's automation, analysis, or intelligent recommendations.",
                "I'll develop AI-based content generation tools that can create blog posts, product descriptions, or social media content based on your parameters.",
                "I will implement computer vision systems that can analyze images or videos for your specific applications, from quality control to security.",
                "I'll create natural language processing solutions that can analyze customer feedback, reviews, or support tickets to extract valuable insights.",
                "I will develop AI-powered recommendation systems for your e-commerce store or content platform to increase user engagement and sales."
            ],
            'lifestyle' => [
                "I will create a personalized fitness plan based on your goals, preferences, and limitations to help you achieve optimal health and wellness.",
                "I'll provide professional nutritional guidance and meal plans tailored to your specific dietary needs, preferences, and health goals.",
                "I will offer virtual life coaching sessions to help you overcome challenges, set meaningful goals, and create actionable plans for success.",
                "I'll plan a memorable virtual event for your team, friends, or family that brings everyone together for fun and connection despite distance.",
                "I will provide virtual interior design consultation to transform your living or working space into a beautiful and functional environment.",
                "I'll offer mindfulness and meditation guidance to help you reduce stress, improve focus, and enhance overall mental well-being.",
                "I will provide personalized fashion and style advice to help you build a wardrobe that enhances your confidence and reflects your personality."
            ]
        ];
        
        $serviceImages = [
            'programming' => [
                'web-development.jpg',
                'app-development.jpg',
                'bug-fixing.jpg',
                'mobile-app.jpg',
                'database-optimization.jpg',
                'api-integration.jpg',
                'wordpress-plugin.jpg'
            ],
            'design' => [
                'logo-design.jpg',
                'social-media-design.jpg',
                'stationery-design.jpg',
                'ui-ux-design.jpg',
                'illustration.jpg',
                'banner-design.jpg',
                'presentation-design.jpg'
            ],
            'digital-marketing' => [
                'digital-strategy.jpg',
                'seo-optimization.jpg',
                'social-media-management.jpg',
                'google-ads.jpg',
                'analytics-reporting.jpg',
                'email-marketing.jpg',
                'brand-identity.jpg'
            ],
            'writing' => [
                'blog-writing.jpg',
                'product-description.jpg',
                'press-release.jpg',
                'translation.jpg',
                'proofreading.jpg',
                'sales-copy.jpg',
                'resume-writing.jpg'
            ],
            'video' => [
                'video-editing.jpg',
                'motion-graphics.jpg',
                'explainer-video.jpg',
                'youtube-intro.jpg',
                'social-media-ads.jpg',
                'photo-editing.jpg',
                'testimonial-video.jpg'
            ],
            'music' => [
                'music-composition.jpg',
                'mixing-mastering.jpg',
                'jingle-creation.jpg',
                'voice-over.jpg',
                'transcription.jpg',
                'podcast-production.jpg',
                'sound-effects.jpg'
            ],
            'business' => [
                'business-plan.jpg',
                'financial-analysis.jpg',
                'market-research.jpg',
                'customer-service.jpg',
                'process-optimization.jpg',
                'marketing-strategy.jpg',
                'virtual-assistant.jpg'
            ],
            'ai-services' => [
                'ai-chatbot.jpg',
                'machine-learning.jpg',
                'custom-ai-solutions.jpg',
                'ai-content-generation.jpg',
                'computer-vision.jpg',
                'natural-language-processing.jpg',
                'recommendation-system.jpg'
            ],
            'lifestyle' => [
                'fitness-plan.jpg',
                'nutrition-plan.jpg',
                'life-coaching.jpg',
                'virtual-event.jpg',
                'interior-design.jpg',
                'meditation-guidance.jpg',
                'fashion-advice.jpg'
            ]
        ];
        
        $id = 1;
        foreach ($this->serviceCategories as $categorySlug => $categoryName) {
            // Skip if category doesn't exist in our mock data
            if (!isset($serviceDescriptions[$categorySlug]) || !isset($serviceImages[$categorySlug])) {
                continue;
            }
            
            $descriptions = $serviceDescriptions[$categorySlug];
            $images = $serviceImages[$categorySlug];
            
            for ($i = 0; $i < count($descriptions); $i++) {
                // Generate a title from the description
                $title = 'I will ' . strtolower(substr($descriptions[$i], 2, strpos($descriptions[$i], '.') - 2));
                
                // Generate a random price between $5 and $500
                $price = rand(5, 50) * 5;
                
                // Generate random rating between 4.0 and 5.0
                $rating = round(4 + (rand(0, 10) / 10), 1);
                
                // Generate random number of reviews
                $reviewCount = rand(5, 500);
                
                // Generate random seller
                $sellerNames = ['John Doe', 'Jane Smith', 'Alex Johnson', 'Sara Williams', 'Michael Brown', 'Emily Davis', 'Robert Wilson', 'Lisa Taylor', 'David Martinez', 'Jennifer Anderson'];
                $sellerLevels = ['Level 1', 'Level 2', 'Top Rated', 'Level 2', 'Level 1', 'Top Rated', 'Level 2', 'Level 1', 'Top Rated', 'Level 2'];
                $sellerIndex = rand(0, count($sellerNames) - 1);
                
                // Generate delivery time
                $deliveryTimes = [1, 2, 3, 5, 7, 10, 14, 21];
                $deliveryTime = $deliveryTimes[rand(0, count($deliveryTimes) - 1)];
                
                // Generate revisions
                $revisions = ['Unlimited', '1', '2', '3', '5', '10'];
                $revision = $revisions[rand(0, count($revisions) - 1)];
                
                $services[] = [
                    'id' => $id++,
                    'title' => $title,
                    'description' => $descriptions[$i],
                    'image' => 'public/img/services/' . $images[$i],
                    'price' => $price,
                    'category' => $categorySlug,
                    'categoryName' => $categoryName,
                    'rating' => $rating,
                    'reviewCount' => $reviewCount,
                    'seller' => [
                        'name' => $sellerNames[$sellerIndex],
                        'level' => $sellerLevels[$sellerIndex],
                        'avatar' => 'public/img/avatars/user' . rand(1, 10) . '.jpg',
                        'responseTime' => rand(1, 24) . ' hours'
                    ],
                    'deliveryTime' => $deliveryTime,
                    'revisions' => $revision,
                    'featured' => rand(0, 10) > 8, // 20% chance of being featured
                    'popular' => rand(0, 10) > 6, // 40% chance of being popular
                    'tags' => $this->generateRandomTags($categorySlug)
                ];
            }
        }
        
        return $services;
    }
    
    // Helper function to filter services
    private function filterServices($search, $category, $minPrice, $maxPrice, $minRating) {
        $filteredServices = [];
        
        foreach ($this->mockServices as $service) {
            // Check if service matches the search term
            $matchesSearch = empty($search) || 
                            stripos($service['title'], $search) !== false || 
                            stripos($service['description'], $search) !== false;
            
            // Check if service is in the selected category
            $matchesCategory = empty($category) || $service['category'] === $category;
            
            // Check if service is in the price range
            $matchesPrice = $service['price'] >= $minPrice && $service['price'] <= $maxPrice;
            
            // Check if service meets the minimum rating
            $matchesRating = $service['rating'] >= $minRating;
            
            // Add service to filtered list if it matches all criteria
            if ($matchesSearch && $matchesCategory && $matchesPrice && $matchesRating) {
                $filteredServices[] = $service;
            }
        }
        
        return $filteredServices;
    }
    
    // Helper function to sort services
    private function sortServices($services, $sortBy) {
        switch ($sortBy) {
            case 'price_low':
                usort($services, function($a, $b) {
                    return $a['price'] - $b['price'];
                });
                break;
                
            case 'price_high':
                usort($services, function($a, $b) {
                    return $b['price'] - $a['price'];
                });
                break;
                
            case 'rating':
                usort($services, function($a, $b) {
                    return $b['rating'] - $a['rating'];
                });
                break;
                
            case 'popular':
            default:
                // Sort by a combination of rating and review count (popularity)
                usort($services, function($a, $b) {
                    $popularityA = $a['rating'] * sqrt($a['reviewCount']);
                    $popularityB = $b['rating'] * sqrt($b['reviewCount']);
                    return $popularityB - $popularityA;
                });
                break;
        }
        
        return $services;
    }
    
    // Helper function to generate random tags for services
    private function generateRandomTags($category) {
        $tagsByCategory = [
            'programming' => ['responsive', 'wordpress', 'php', 'javascript', 'html', 'css', 'mobile', 'react', 'vue', 'angular', 'nodejs', 'laravel', 'api', 'ecommerce', 'shopify', 'wix', 'webflow', 'seo', 'database', 'mysql', 'mongodb', 'postgresql', 'firebase', 'aws', 'cloud', 'docker', 'git', 'agile'],
            
            'design' => ['logo', 'branding', 'identity', 'social media', 'instagram', 'facebook', 'twitter', 'youtube', 'banner', 'business card', 'flyer', 'poster', 'infographic', 'vector', 'illustration', 'icon', 'mascot', 'character', 'ui', 'ux', 'user interface', 'user experience', 'web design', 'mobile design', 'app design', 'figma', 'sketch', 'adobe', 'photoshop', 'illustrator'],
            
            'digital-marketing' => ['seo', 'sem', 'ppc', 'google ads', 'facebook ads', 'instagram ads', 'social media', 'content marketing', 'email marketing', 'lead generation', 'conversion optimization', 'analytics', 'reporting', 'strategy', 'planning', 'branding', 'market research', 'competitor analysis', 'keyword research', 'link building', 'on-page seo', 'off-page seo', 'local seo', 'ecommerce marketing', 'influencer marketing'],
            
            'writing' => ['blog', 'article', 'content', 'copywriting', 'seo writing', 'creative writing', 'technical writing', 'product description', 'website content', 'social media content', 'press release', 'ebook', 'ghostwriting', 'editing', 'proofreading', 'translation', 'transcription', 'resume', 'cover letter', 'business plan', 'research', 'academic writing', 'scientific writing', 'medical writing', 'legal writing'],
            
            'video' => ['editing', 'production', 'animation', 'motion graphics', 'explainer video', 'whiteboard animation', 'intro', 'outro', 'logo animation', 'promotional video', 'commercial', 'testimonial', 'product video', 'social media video', 'youtube', 'tiktok', 'instagram', 'facebook', 'after effects', 'premiere pro', 'final cut pro', 'davinci resolve', 'color grading', 'sound design', 'green screen'],
            
            'music' => ['composition', 'production', 'mixing', 'mastering', 'recording', 'sound design', 'jingle', 'theme song', 'background music', 'stock music', 'podcast music', 'intro music', 'outro music', 'voice over', 'narration', 'singer', 'songwriter', 'producer', 'beats', 'instrumental', 'vocal processing', 'autotune', 'melody', 'harmony', 'lyrics'],
            
            'business' => ['consulting', 'strategy', 'planning', 'business plan', 'financial analysis', 'market research', 'competitor analysis', 'swot analysis', 'customer service', 'virtual assistant', 'data entry', 'admin support', 'project management', 'operations', 'human resources', 'recruitment', 'training', 'coaching', 'mentoring', 'presentation', 'pitch deck', 'sales', 'lead generation', 'crm', 'customer relationship'],
            
            'ai-services' => ['artificial intelligence', 'machine learning', 'deep learning', 'neural networks', 'natural language processing', 'nlp', 'computer vision', 'chatbot', 'virtual assistant', 'automation', 'data analysis', 'predictive modeling', 'classification', 'regression', 'clustering', 'recommendation system', 'sentiment analysis', 'entity recognition', 'object detection', 'face recognition', 'speech recognition', 'text to speech', 'ai integration', 'openai', 'gpt', 'bert', 'transformers'],
            
            'lifestyle' => ['fitness', 'workout', 'training', 'nutrition', 'diet', 'meal plan', 'wellness', 'yoga', 'meditation', 'mindfulness', 'life coaching', 'personal development', 'motivation', 'productivity', 'time management', 'stress management', 'mental health', 'relationship advice', 'dating', 'fashion', 'style', 'interior design', 'home decor', 'event planning', 'party planning', 'travel planning', 'virtual events']
        ];
        
        // Use default tags if category doesn't exist in our data
        if (!isset($tagsByCategory[$category])) {
            return ['general', 'service', 'professional'];
        }
        
        $allTags = $tagsByCategory[$category];
        $numTags = rand(3, 6); // Random number of tags between 3 and 6
        
        // Get random tags
        shuffle($allTags);
        $selectedTags = array_slice($allTags, 0, $numTags);
        
        return $selectedTags;
    }

    /**
     * Generate experts for a specific category
     * 
     * @param string $category Category to generate experts for
     * @param int $count Number of services in that category
     * @return array Array of expert profiles
     */
    private function generateExperts($category, $count = 10) {
        // If count is less than 5, generate at least 5 experts
        $expertCount = max(5, min(20, $count));
        
        // Names for generating experts
        $firstNames = ['James', 'Mary', 'John', 'Patricia', 'Robert', 'Jennifer', 'Michael', 'Linda', 'William', 'Elizabeth', 
                      'David', 'Barbara', 'Richard', 'Susan', 'Joseph', 'Jessica', 'Thomas', 'Sarah', 'Charles', 'Karen',
                      'Daniel', 'Nancy', 'Matthew', 'Lisa', 'Anthony', 'Margaret', 'Mark', 'Betty', 'Donald', 'Sandra',
                      'Steven', 'Ashley', 'Paul', 'Emily', 'Andrew', 'Donna', 'Joshua', 'Michelle', 'Kenneth', 'Carol'];
        
        $lastNames = ['Smith', 'Johnson', 'Williams', 'Brown', 'Jones', 'Garcia', 'Miller', 'Davis', 'Rodriguez', 'Martinez',
                      'Hernandez', 'Lopez', 'Gonzalez', 'Wilson', 'Anderson', 'Thomas', 'Taylor', 'Moore', 'Jackson', 'Martin',
                      'Lee', 'Perez', 'Thompson', 'White', 'Harris', 'Sanchez', 'Clark', 'Ramirez', 'Lewis', 'Robinson',
                      'Walker', 'Young', 'Allen', 'King', 'Wright', 'Scott', 'Torres', 'Nguyen', 'Hill', 'Flores'];
                      
        $locations = ['New York, NY', 'Los Angeles, CA', 'Chicago, IL', 'Houston, TX', 'Phoenix, AZ', 
                     'Philadelphia, PA', 'San Antonio, TX', 'San Diego, CA', 'Dallas, TX', 'San Jose, CA',
                     'Austin, TX', 'Jacksonville, FL', 'Fort Worth, TX', 'Columbus, OH', 'San Francisco, CA',
                     'Charlotte, NC', 'Indianapolis, IN', 'Seattle, WA', 'Denver, CO', 'Boston, MA',
                     'London, UK', 'Sydney, AU', 'Toronto, CA', 'Berlin, DE', 'Paris, FR'];
        
        // Titles and specialties by category
        $expertTitles = [
            'programming' => [
                'Full Stack Developer', 'Front-End Engineer', 'Back-End Developer', 'Mobile App Developer', 
                'WordPress Developer', 'Database Engineer', 'DevOps Specialist', 'Node.js Expert',
                'React Developer', 'Angular Specialist', 'Python Developer', 'Java Architect',
                'PHP Developer', 'Shopify Expert', 'Ruby on Rails Developer', 'Unity Game Developer'
            ],
            'design' => [
                'UI/UX Designer', 'Graphic Designer', 'Brand Identity Expert', 'Logo Designer',
                'Web Designer', 'Product Designer', 'Illustrator', 'Art Director',
                'Motion Graphics Designer', '3D Designer', 'UX Researcher', 'Print Designer',
                'Packaging Designer', 'Icon Designer', 'Infographic Designer', 'Typographer'
            ],
            'digital-marketing' => [
                'SEO Specialist', 'Social Media Manager', 'Content Strategist', 'PPC Expert',
                'Email Marketing Specialist', 'Digital Marketing Consultant', 'Growth Hacker',
                'Conversion Rate Optimizer', 'Marketing Automation Expert', 'Google Ads Specialist',
                'Analytics Expert', 'Social Media Ads Specialist', 'Affiliate Marketing Expert',
                'Local SEO Expert', 'E-commerce Marketing Specialist', 'Content Marketing Manager'
            ],
            'writing' => [
                'Content Writer', 'Copywriter', 'Technical Writer', 'SEO Writer',
                'Creative Writer', 'Ghostwriter', 'Script Writer', 'Editor',
                'Proofreader', 'Blog Writer', 'Grant Writer', 'Resume Writer',
                'Product Description Writer', 'Legal Writer', 'Medical Writer', 'Academic Writer'
            ],
            'video' => [
                'Video Editor', 'Videographer', 'Motion Graphics Designer', 'YouTube Specialist',
                'Video Animator', 'Video Production Consultant', '3D Animator', 'Video Marketing Expert',
                'Documentary Filmmaker', 'Video Colorist', 'Drone Videographer', 'After Effects Specialist',
                'Video Script Writer', 'Social Media Video Creator', 'Live Streaming Expert', 'Video Strategy Consultant'
            ],
            'music' => [
                'Music Producer', 'Audio Engineer', 'Voice Over Artist', 'Songwriter',
                'Composer', 'Sound Designer', 'Mixing & Mastering Engineer', 'Podcast Producer',
                'Music Transcriber', 'Singer', 'Music Theory Tutor', 'Recording Engineer',
                'Jingle Creator', 'Music Marketing Consultant', 'Music Arranger', 'EDM Producer'
            ],
            'business' => [
                'Business Consultant', 'Financial Advisor', 'Virtual Assistant', 'Business Plan Writer',
                'Market Researcher', 'Project Manager', 'Data Analyst', 'Lean Six Sigma Consultant',
                'Business Coach', 'HR Consultant', 'Sales Strategy Consultant', 'Operations Consultant',
                'Supply Chain Specialist', 'Business Development Expert', 'E-commerce Consultant', 'Startup Advisor'
            ],
            'ai-services' => [
                'AI Developer', 'Machine Learning Engineer', 'AI Consultant', 'Chatbot Developer',
                'NLP Specialist', 'Computer Vision Expert', 'Data Scientist', 'AI Strategy Consultant',
                'AI Implementation Specialist', 'AI Ethics Consultant', 'Deep Learning Engineer',
                'AI for Business Consultant', 'AI Researcher', 'AI Product Manager', 'AI Content Specialist', 'AI UX Designer'
            ],
            'lifestyle' => [
                'Fitness Trainer', 'Nutritionist', 'Life Coach', 'Interior Designer',
                'Event Planner', 'Personal Stylist', 'Meditation Coach', 'Career Coach',
                'Travel Consultant', 'Relationship Coach', 'Wellness Consultant', 'Yoga Instructor',
                'Health Coach', 'Personal Chef', 'Fashion Consultant', 'Productivity Coach'
            ]
        ];
        
        // Default to programming if category not found
        $categoryTitles = isset($expertTitles[$category]) ? $expertTitles[$category] : $expertTitles['programming'];
        
        // Generate random experts
        $experts = [];
        $usedNames = [];
        
        for ($i = 0; $i < $expertCount; $i++) {
            // Generate unique full name
            do {
                $firstName = $firstNames[array_rand($firstNames)];
                $lastName = $lastNames[array_rand($lastNames)];
                $fullName = $firstName . ' ' . $lastName;
            } while (in_array($fullName, $usedNames));
            
            $usedNames[] = $fullName;
            
            // Create avatar URL - using placeholders
            $avatarSeed = strtolower(str_replace(' ', '-', $fullName));
            $avatarUrl = "https://i.pravatar.cc/150?u=" . md5($avatarSeed);
            
            // Generate random hourly rate between $30-150
            $hourlyRate = rand(30, 150);
            
            // Generate random ratings between 4.0 and 5.0
            $rating = round(4 + (rand(0, 100) / 100), 1);
            
            // Generate random review count
            $reviewCount = rand(5, 100);
            
            // Generate completed projects count
            $completedProjects = rand(10, 200);
            
            // Select a random title from the category
            $title = $categoryTitles[array_rand($categoryTitles)];
            
            // Generate a random location
            $location = $locations[array_rand($locations)];
            
            // Generate random availability (30-100%)
            $availability = rand(30, 100);
            
            // Add to the experts array
            $experts[] = [
                'id' => $i + 1,
                'name' => $fullName,
                'avatar' => $avatarUrl,
                'title' => $title,
                'category' => $category,
                'hourly_rate' => $hourlyRate,
                'rating' => $rating,
                'review_count' => $reviewCount,
                'location' => $location,
                'completed_projects' => $completedProjects,
                'availability' => $availability,
                'response_time' => rand(1, 24) . ' hours',
                'member_since' => date('M Y', strtotime("-" . rand(1, 60) . " months")),
                'description' => "Expert " . $title . " with " . rand(2, 20) . "+ years of experience. Specialized in creating high-quality solutions for clients worldwide. Fast turnaround times and excellent communication."
            ];
        }
        
        return $experts;
    }
} 