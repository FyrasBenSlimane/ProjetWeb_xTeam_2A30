<?php
// Set up basic error reporting and component paths
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define the components with absolute paths
$componentsPath = __DIR__ . '/components';
$components = [
    'navbar' => $componentsPath . '/navbar.php',
    'footer' => $componentsPath . '/footer.php'
];
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=5.0, minimum-scale=1.0">
    <meta name="description" content="Service Details - LenSi Freelance Marketplace">
    <meta name="theme-color" content="#3E5C76">
    <title>Service Details | LenSi</title>
    <link rel="icon" type="image/svg+xml" href="assets/images/logo_white.svg" sizes="any">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;500;600;700&family=Inter:wght@300;400;500&family=Poppins:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">

<style>
/* Root variables and global styles */
:root {
    --primary: #3E5C76;
    --primary-rgb: 62, 92, 118;
    --secondary: #748CAB;
    --accent: #1D2D44;
    --accent-dark: #0D1B2A;
    --light: #F9F7F0;
    --dark: #0D1B2A;
    --font-primary: 'Montserrat', sans-serif;
    --font-secondary: 'Inter', sans-serif;
    --font-heading: 'Poppins', sans-serif;
    --transition-default: all 0.3s ease;
    --transition-bounce: all 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

/* Enhanced Service Detail Styles */
.service-detail-header {
    background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
    padding: 6rem 0 4rem;
    color: white;
    position: relative;
    overflow: hidden;
}

.service-detail-header::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background: url('data:image/svg+xml,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100"><rect fill="rgba(255,255,255,0.1)" width="100" height="100"/></svg>') repeat;
    opacity: 0.1;
    animation: backgroundMove 20s linear infinite;
}

@keyframes backgroundMove {
    0% {
        background-position: 0 0;
    }
    100% {
        background-position: 100px 100px;
    }
}

.service-detail-container {
    max-width: var(--container-width, 1400px);
    margin: 0 auto;
    padding: 0 1.5rem;
    position: relative;
}

.service-detail-grid {
    display: grid;
    grid-template-columns: minmax(0, 2fr) minmax(0, 1fr);
    gap: 2rem;
    margin: 2rem 0;
    position: relative;
}

.service-detail-main {
    background: white;
    border-radius: 1rem;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    transition: var(--transition-default);
    overflow: hidden;
}

.service-detail-tabs {
    display: flex;
    gap: 1rem;
    padding: 1rem 2rem;
    border-bottom: 1px solid rgba(0,0,0,0.1);
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(10px);
    position: sticky;
    top: 80px;
    z-index: 10;
}

.service-detail-tab {
    padding: 0.75rem 1.5rem;
    border: none;
    background: none;
    color: var(--secondary);
    font-weight: 500;
    position: relative;
    transition: var(--transition-default);
    cursor: pointer;
}

.service-detail-tab:hover {
    color: var(--primary);
}

.service-detail-tab.active {
    color: var(--primary);
}

.service-detail-tab.active::after {
    content: '';
    position: absolute;
    bottom: -1rem;
    left: 0;
    width: 100%;
    height: 3px;
    background: var(--primary);
    border-radius: 3px 3px 0 0;
}

.service-detail-content {
    padding: 2rem;
}

.service-detail-image {
    width: 100%;
    height: 500px;
    object-fit: cover;
    border-radius: 1rem;
    margin-bottom: 2rem;
    transition: var(--transition-default);
}

.service-detail-image:hover {
    transform: scale(1.02);
}

.service-detail-seller {
    display: flex;
    align-items: center;
    gap: 1.5rem;
    margin-bottom: 2rem;
    padding: 1.5rem;
    border-radius: 1rem;
    background: rgba(var(--primary-rgb), 0.05);
    transition: var(--transition-default);
}

.service-detail-seller:hover {
    background: rgba(var(--primary-rgb), 0.1);
}

.service-detail-seller img {
    width: 80px;
    height: 80px;
    border-radius: 50%;
    object-fit: cover;
    border: 3px solid white;
    box-shadow: 0 3px 10px rgba(0,0,0,0.1);
}

.service-stats {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
    gap: 1rem;
    margin-bottom: 2rem;
}

.stat-item {
    padding: 1rem;
    border-radius: 0.5rem;
    background: white;
    box-shadow: 0 3px 10px rgba(0,0,0,0.05);
    transition: var(--transition-bounce);
    display: flex;
    align-items: center;
    gap: 0.75rem;
}

.stat-item:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.stat-item i {
    font-size: 1.5rem;
    color: var(--primary);
}

.features-list {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 1rem;
    margin: 1.5rem 0;
}

.feature-item {
    display: flex;
    align-items: flex-start;
    gap: 1rem;
    padding: 1rem;
    border-radius: 0.5rem;
    background: white;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    transition: var(--transition-bounce);
}

.feature-item:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}

.feature-item i {
    color: #22C55E;
    font-size: 1.25rem;
    flex-shrink: 0;
}

.pricing-card {
    background: white;
    border-radius: 1rem;
    padding: 2rem;
    box-shadow: 0 5px 20px rgba(0,0,0,0.05);
    position: sticky;
    top: 100px;
    transition: var(--transition-bounce);
}

.pricing-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.1);
}

.pricing-header {
    display: flex;
    justify-content: space-between;
    align-items: baseline;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid rgba(0,0,0,0.1);
}

.price-amount {
    font-size: 2.5rem;
    font-weight: 700;
    color: var(--primary);
}

.delivery-info {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 1rem;
    background: rgba(var(--primary-rgb), 0.05);
    border-radius: 0.5rem;
    margin-bottom: 1.5rem;
}

.delivery-info i {
    color: var(--primary);
}

.action-buttons {
    display: flex;
    flex-direction: column;
    gap: 1rem;
}

.btn-primary {
    background: var(--primary);
    color: white;
    border: none;
    padding: 1rem;
    border-radius: 0.5rem;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition-bounce);
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-primary:hover {
    background: var(--accent);
    transform: translateY(-2px);
}

.btn-outline {
    background: transparent;
    color: var(--primary);
    border: 2px solid var(--primary);
    padding: 1rem;
    border-radius: 0.5rem;
    font-weight: 600;
    cursor: pointer;
    transition: var(--transition-bounce);
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 0.5rem;
}

.btn-outline:hover {
    background: rgba(var(--primary-rgb), 0.1);
    transform: translateY(-2px);
}

/* Reviews Section Enhancement */
.reviews-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
    padding-bottom: 1rem;
    border-bottom: 1px solid rgba(0,0,0,0.1);
}

.reviews-summary {
    display: flex;
    align-items: baseline;
    gap: 1rem;
}

.rating-summary {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.star-rating {
    color: #FFD700;
}

.review-card {
    background: white;
    border-radius: 1rem;
    padding: 1.5rem;
    margin-bottom: 1.5rem;
    transition: var(--transition-default);
    border: 1px solid rgba(0,0,0,0.05);
}

.review-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.review-header {
    display: flex;
    align-items: center;
    gap: 1rem;
    margin-bottom: 1rem;
}

.reviewer-avatar {
    width: 50px;
    height: 50px;
    border-radius: 50%;
    object-fit: cover;
}

.review-rating {
    display: flex;
    align-items: center;
    gap: 0.25rem;
    margin-top: 0.5rem;
}

/* Enhanced About Seller Section */
.seller-skills {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
    margin-top: 1rem;
}

.skill-tag {
    background: rgba(var(--primary-rgb), 0.1);
    color: var(--primary);
    padding: 0.5rem 1rem;
    border-radius: 2rem;
    font-size: 0.9rem;
    transition: var(--transition-default);
}

.skill-tag:hover {
    background: rgba(var(--primary-rgb), 0.2);
    transform: translateY(-2px);
}

/* Dark mode adjustments */
[data-bs-theme="dark"] {
    .service-detail-header {
        background: linear-gradient(135deg, var(--accent-dark) 0%, var(--primary) 100%);
    }

    .service-detail-main,
    .service-detail-card,
    .pricing-card,
    .stat-item,
    .feature-item,
    .review-card {
        background: rgba(31, 32, 40, 0.8);
        border-color: rgba(255, 255, 255, 0.05);
    }

    .service-detail-tabs {
        background: rgba(31, 32, 40, 0.95);
    }

    .service-detail-seller {
        background: rgba(255, 255, 255, 0.05);
    }

    .btn-outline {
        border-color: var(--secondary);
        color: var(--secondary);
    }

    .skill-tag {
        background: rgba(255, 255, 255, 0.1);
        color: var(--secondary);
    }
}

/* Responsive Adjustments */
@media (max-width: 992px) {
    .service-detail-grid {
        grid-template-columns: 1fr;
    }

    .service-detail-tabs {
        top: 70px;
        padding: 1rem;
    }

    .service-detail-tab {
        padding: 0.5rem 1rem;
    }

    .pricing-card {
        position: relative;
        top: 0;
        margin-bottom: 2rem;
    }

    .service-detail-image {
        height: 400px;
    }
}

@media (max-width: 768px) {
    .service-detail-header {
        padding: 4rem 0 2rem;
    }

    .service-detail-content {
        padding: 1rem;
    }

    .service-detail-image {
        height: 300px;
    }

    .service-stats {
        grid-template-columns: repeat(2, 1fr);
    }

    .features-list {
        grid-template-columns: 1fr;
    }
}

@media (max-width: 576px) {
    .service-detail-tabs {
        flex-wrap: wrap;
    }

    .service-detail-tab {
        flex: 1 1 auto;
        text-align: center;
    }

    .service-detail-seller {
        flex-direction: column;
        text-align: center;
        padding: 1rem;
    }

    .service-stats {
        grid-template-columns: 1fr;
    }
}
</style>

<!-- Navbar Component -->
<?php include $components['navbar']; ?>

<!-- Service Detail Content -->
<div class="service-detail-header">
    <div class="service-detail-container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php" class="text-white">Home</a></li>
                <li class="breadcrumb-item"><a href="services.php" class="text-white">Services</a></li>
                <li class="breadcrumb-item active text-white" aria-current="page">Service Details</li>
            </ol>
        </nav>
        <h1 class="service-detail-title"></h1>
    </div>
</div>

<div class="service-detail-container">
    <div class="service-detail-grid">
        <!-- Main Content -->
        <div class="service-detail-main">
            <div class="service-detail-tabs">
                <button class="service-detail-tab active" data-tab="description">Description</button>
                <button class="service-detail-tab" data-tab="about">About the Seller</button>
                <button class="service-detail-tab" data-tab="reviews">Reviews</button>
            </div>
            
            <div class="service-detail-content">
                <!-- Description Tab -->
                <div class="tab-content active" id="description">
                    <img src="" alt="" class="service-detail-image" id="serviceImage">
                    
                    <div class="service-detail-seller">
                        <img src="" alt="" id="sellerAvatar">
                        <div class="service-detail-seller-info">
                            <h3 id="sellerName"></h3>
                            <p id="sellerLevel"></p>
                        </div>
                    </div>
                    
                    <div class="service-stats">
                        <div class="stat-item">
                            <i class="bi bi-star-fill"></i>
                            <span id="serviceRating"></span>
                        </div>
                        <div class="stat-item">
                            <i class="bi bi-trophy"></i>
                            <span id="completedProjects"></span>
                        </div>
                    </div>
                    
                    <h2>About This Service</h2>
                    <p id="serviceDescription"></p>
                    
                    <h3>What's Included:</h3>
                    <ul class="service-features" id="serviceFeatures">
                    </ul>
                </div>
                
                <!-- About Tab -->
                <div class="tab-content" id="about" style="display: none;">
                    <div class="seller-about">
                        <div class="seller-header">
                            <img src="" alt="" id="sellerAboutAvatar">
                            <div>
                                <h2 id="sellerAboutName"></h2>
                                <p id="sellerAboutBio"></p>
                            </div>
                        </div>
                        
                        <div class="seller-stats">
                            <div class="stat-grid">
                                <div class="stat-item">
                                    <strong>From</strong>
                                    <span id="sellerCountry"></span>
                                </div>
                                <div class="stat-item">
                                    <strong>Member since</strong>
                                    <span id="sellerJoinDate"></span>
                                </div>
                                <div class="stat-item">
                                    <strong>Languages</strong>
                                    <span id="sellerLanguages"></span>
                                </div>
                                <div class="stat-item">
                                    <strong>Response time</strong>
                                    <span>Within a few hours</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="seller-skills">
                            <h3>Skills</h3>
                            <div class="skill-tags" id="sellerSkills">
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Reviews Tab -->
                <div class="tab-content" id="reviews" style="display: none;">
                    <div class="reviews-header">
                        <div class="reviews-summary">
                            <h2><span id="reviewCount"></span> Reviews</h2>
                            <div class="rating-summary">
                                <i class="bi bi-star-fill"></i>
                                <strong id="averageRating"></strong>
                                <span>overall</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="reviews-list" id="reviewsList">
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Sidebar -->
        <div class="service-detail-sidebar">
            <div class="pricing-card">
                <div class="pricing-header">
                    <div class="price-amount" id="servicePrice"></div>
                    <span>per project</span>
                </div>
                
                <div class="delivery-info">
                    <i class="bi bi-clock"></i>
                    <span id="deliveryTime"></span>
                </div>
                
                <div class="service-features-list" id="pricingFeatures">
                </div>
                
                <div class="action-buttons">
                    <button class="btn-primary">Order Now</button>
                    <button class="btn-outline">
                        <i class="bi bi-chat"></i>
                        Contact Seller
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Footer Component -->
<?php include $components['footer']; ?>

<!-- Enhanced JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
// The existing JavaScript with added animations and interactions
document.addEventListener('DOMContentLoaded', function() {
    // Initialize theme
    function initializeTheme() {
        const savedTheme = localStorage.getItem('theme');
        const prefersDarkScheme = window.matchMedia('(prefers-color-scheme: dark)');
        const themeToUse = savedTheme || (prefersDarkScheme.matches ? 'dark' : 'light');
        document.documentElement.setAttribute('data-bs-theme', themeToUse);
    }

    initializeTheme();

    // Function to load service details with enhanced animations
    async function loadServiceDetails(serviceId) {
        // Simulate loading state with fade effect
        const mainContent = document.querySelector('.service-detail-main');
        const pricingCard = document.querySelector('.pricing-card');
        
        mainContent.style.opacity = '0';
        pricingCard.style.opacity = '0';
        
        // Get service data (mock data for example)
        const serviceData = {
            id: serviceId,
            title: "Professional Website Development",
            price: 299,
            rating: 4.9,
            ratingCount: 128,
            deliveryTime: "5 days delivery",
            image: "https://images.unsplash.com/photo-1587440871875-191322ee64b0",
            description: "I will create a responsive, modern website for your business or personal brand using the latest technologies.",
            features: [
                "Custom design tailored to your brand",
                "Responsive layout for all devices",
                "SEO optimization",
                "Modern UI/UX practices",
                "Source code included"
            ],
            seller: {
                name: "Alex Mitchell",
                level: "Level 2 Seller",
                avatar: "https://randomuser.me/api/portraits/men/32.jpg",
                rating: 4.9,
                completedProjects: 156,
                country: "United States",
                memberSince: "Jan 2022",
                languages: ["English", "Spanish"],
                skills: ["HTML", "CSS", "JavaScript", "React", "Node.js"],
                bio: "Full-stack developer with 5+ years of experience in creating modern web applications."
            }
        };

        // Add animation delay
        setTimeout(() => {
            mainContent.style.opacity = '1';
            pricingCard.style.opacity = '1';
            
            // Update page content with enhanced animations
            document.querySelector('.service-detail-title').textContent = serviceData.title;
            
            // Add smooth reveal animations for content sections
            const elements = document.querySelectorAll('.service-detail-content > *');
            elements.forEach((el, index) => {
                el.style.opacity = '0';
                el.style.transform = 'translateY(20px)';
                setTimeout(() => {
                    el.style.transition = 'all 0.5s ease';
                    el.style.opacity = '1';
                    el.style.transform = 'translateY(0)';
                }, 100 * index);
            });
        }, 300);

        // Update page content with service details
        document.title = serviceData.title + " | LenSi";
        document.querySelector('.service-detail-title').textContent = serviceData.title;
        document.getElementById('serviceImage').src = serviceData.image;
        document.getElementById('serviceImage').alt = serviceData.title;
        document.getElementById('servicePrice').textContent = '$' + serviceData.price;
        document.getElementById('deliveryTime').textContent = serviceData.deliveryTime;
        document.getElementById('serviceDescription').textContent = serviceData.description;
        document.getElementById('sellerName').textContent = serviceData.seller.name;
        document.getElementById('sellerLevel').textContent = serviceData.seller.level;
        document.getElementById('sellerAvatar').src = serviceData.seller.avatar;
        document.getElementById('sellerAvatar').alt = serviceData.seller.name;
        document.getElementById('serviceRating').textContent = serviceData.rating + ' (' + serviceData.ratingCount + ' reviews)';
        document.getElementById('completedProjects').textContent = serviceData.seller.completedProjects + ' projects completed';

        // Update seller information
        document.getElementById('sellerAboutName').textContent = serviceData.seller.name;
        document.getElementById('sellerAboutBio').textContent = serviceData.seller.bio;
        document.getElementById('sellerAboutAvatar').src = serviceData.seller.avatar;
        document.getElementById('sellerCountry').textContent = serviceData.seller.country;
        document.getElementById('sellerJoinDate').textContent = serviceData.seller.memberSince;
        document.getElementById('sellerLanguages').textContent = serviceData.seller.languages.join(', ');

        // Update features list
        const featuresList = document.getElementById('serviceFeatures');
        featuresList.innerHTML = serviceData.features.map(feature => 
            `<li><i class="bi bi-check-circle-fill text-success"></i> ${feature}</li>`
        ).join('');

        // Update skills
        const skillsContainer = document.getElementById('sellerSkills');
        skillsContainer.innerHTML = serviceData.seller.skills.map(skill => 
            `<span class="badge bg-secondary">${skill}</span>`
        ).join('');
    }

    // Enhanced tab switching with smooth transitions
    const tabs = document.querySelectorAll('.service-detail-tab');
    const tabContents = document.querySelectorAll('.tab-content');

    tabs.forEach(tab => {
        tab.addEventListener('click', () => {
            tabs.forEach(t => t.classList.remove('active'));
            tab.classList.add('active');
            
            // Fade out all tab contents
            tabContents.forEach(content => {
                content.style.opacity = '0';
                setTimeout(() => {
                    content.style.display = 'none';
                }, 300);
            });
            
            // Fade in selected tab content
            const selectedTab = tab.getAttribute('data-tab');
            const selectedContent = document.getElementById(selectedTab);
            setTimeout(() => {
                selectedContent.style.display = 'block';
                setTimeout(() => {
                    selectedContent.style.opacity = '1';
                }, 50);
            }, 300);
        });
    });

    // Add scroll animations
    window.addEventListener('scroll', () => {
        const scrollPosition = window.scrollY;
        
        // Parallax effect for service image
        const serviceImage = document.querySelector('.service-detail-image');
        if (serviceImage) {
            serviceImage.style.transform = `scale(${1 + scrollPosition * 0.0002})`;
        }
        
        // Fade in elements as they come into view
        const fadeElements = document.querySelectorAll('.stat-item, .feature-item, .review-card');
        fadeElements.forEach(element => {
            const elementTop = element.getBoundingClientRect().top;
            if (elementTop < window.innerHeight - 100) {
                element.style.opacity = '1';
                element.style.transform = 'translateY(0)';
            }
        });
    });

    // Initialize animations
    const urlParams = new URLSearchParams(window.location.search);
    const serviceId = urlParams.get('id');
    
    if (serviceId) {
        loadServiceDetails(serviceId);
    }
});
</script>
</html>