<?php
/**
 * Content Sections Component with Frame-based Design
 * Contains the HTML and CSS for the main content sections of the homepage
 */
?>
<style>
/* Frame-based Section Design System */
.section-frame {
    position: relative;
    min-height: 100vh;
    display: flex;
    align-items: center;
    padding: 6rem 0;
    overflow: hidden;
    scroll-margin-top: 80px; /* Ensures proper scroll positioning with fixed navbar */
}

.section-frame:nth-child(even) {
    background-color: rgba(247, 248, 250, 0.5);
}

[data-bs-theme="dark"] .section-frame:nth-child(even) {
    background-color: rgba(18, 21, 30, 0.5);
}

.section-frame-content {
    width: 100%;
    max-width: 1400px; /* Increased from 1200px to make content wider */
    margin: 0 auto;
    padding: 0 1rem; /* Reduced from 2rem to decrease empty space on sides */
    z-index: 2;
    position: relative;
    transition: max-width 0.3s ease;
}

/* Adjust section frame width when scrolled past hero for consistency with navbar */
.section-frame:not(#home) + .section-frame .section-frame-content {
    max-width: 1200px; /* Increased from 1000px to make content wider */
}

.section-frame-header {
    text-align: center;
    margin-bottom: 3rem;
    position: relative;
}

.section-frame-title {
    font-size: 2.5rem;
    font-weight: 700;
    margin-bottom: 1rem;
    position: relative;
    display: inline-block;
    color: var(--accent);
}

.section-frame-title::after {
    content: '';
    position: absolute;
    left: 50%;
    bottom: -0.5rem;
    width: 50px;
    height: 3px;
    background: linear-gradient(90deg, #5D8BB3, #8FB3DE);
    transform: translateX(-50%);
}

[data-bs-theme="dark"] .section-frame-title::after {
    background: linear-gradient(90deg, #7BA4CD, #A8C8E8);
}

.section-frame-subtitle {
    font-size: 1.2rem;
    max-width: 700px;
    margin: 0 auto;
    color: var(--secondary);
}

.section-corner-decoration {
    position: absolute;
    width: 300px;
    height: 300px;
    z-index: 1;
    opacity: 0.1;
    pointer-events: none;
}

.section-corner-decoration-1 {
    top: -100px;
    right: -100px;
    background: linear-gradient(135deg, transparent, rgba(93, 139, 179, 0.3));
    transform: rotate(45deg);
    border-radius: 50px;
}

.section-corner-decoration-2 {
    bottom: -100px;
    left: -100px;
    background: linear-gradient(45deg, rgba(93, 139, 179, 0.3), transparent);
    transform: rotate(45deg);
    border-radius: 50px;
}

/* Section transition indicator */
.section-transition {
    position: absolute;
    bottom: 0;
    left: 0;
    width: 100%;
    height: 60px;
    z-index: 5;
    pointer-events: none;
}

.section-transition-indicator {
    position: absolute;
    left: 50%;
    bottom: 1.5rem;
    transform: translateX(-50%);
    color: var(--primary);
    font-size: 1.5rem;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: white;
    border-radius: 50%;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    animation: float 2s ease-in-out infinite;
    z-index: 10;
    pointer-events: auto;
    cursor: pointer;
    transition: all 0.3s ease;
}

[data-bs-theme="dark"] .section-transition-indicator {
    background-color: rgba(30, 35, 45, 0.95);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3), 0 0 10px rgba(93, 139, 179, 0.2);
    color: #8FB3DE;
}

.section-transition-indicator:hover {
    transform: translateX(-50%) translateY(-5px);
    box-shadow: 0 8px 20px rgba(93, 139, 179, 0.2);
    color: #8FB3DE;
}

[data-bs-theme="dark"] .section-transition-indicator:hover {
    color: #A8C8E8;
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.4), 0 0 15px rgba(93, 139, 179, 0.3);
}

/* Animation for section components */
.section-animate {
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.8s ease-out;
}

.section-animate.visible {
    opacity: 1;
    transform: translateY(0);
}

/* Category Grid Styles (enhanced for frame design) */
.category-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    gap: 1.5rem;
    margin-top: 1.5rem;
}

.category-card {
    background: white;
    border-radius: 15px;
    padding: 2rem 1.5rem;
    text-align: center;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    text-decoration: none;
    color: #1D2D44;
    position: relative;
    overflow: hidden;
    border: 1px solid rgba(0, 0, 0, 0.03);
    backdrop-filter: blur(5px);
}

.category-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(135deg, rgba(116, 140, 171, 0.1), rgba(62, 92, 118, 0.1));
    opacity: 0;
    transition: opacity 0.3s ease;
    z-index: 0;
}

.category-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
}

.category-card:hover::before {
    opacity: 1;
}

.category-icon {
    font-size: 2.5rem;
    color: #3E5C76;
    margin-bottom: 1rem;
    position: relative;
    z-index: 1;
    transition: transform 0.3s ease;
}

.category-card:hover .category-icon {
    transform: scale(1.1);
}

.category-card h3 {
    font-size: 1.2rem;
    font-weight: 600;
    margin-bottom: 0.5rem;
    position: relative;
    z-index: 1;
}

.category-card p {
    color: #748CAB;
    font-size: 0.9rem;
    position: relative;
    z-index: 1;
}

/* Dark mode adjustments */
[data-bs-theme="dark"] .category-card {
    background: rgba(31, 32, 40, 0.8);
    border-color: rgba(70, 90, 120, 0.2);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

[data-bs-theme="dark"] .category-card h3 {
    color: #FFFFFF;
}

[data-bs-theme="dark"] .category-card p {
    color: #A4C2E5;
}

[data-bs-theme="dark"] .category-icon {
    color: #8FB3DE;
}

/* Service Grid Styles */
.service-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.service-card {
    background: white;
    border-radius: 15px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    text-decoration: none;
    border: 1px solid rgba(0, 0, 0, 0.03);
    display: flex;
    flex-direction: column;
    height: 100%;
}

.service-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
}

.service-card-img {
    height: 200px;
    background-size: cover;
    background-position: center;
    position: relative;
    overflow: hidden;
}

.service-card-img::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: linear-gradient(to bottom, transparent 70%, rgba(0, 0, 0, 0.7));
    z-index: 1;
}

.service-card-rating {
    position: absolute;
    top: 1rem;
    right: 1rem;
    background: rgba(255, 255, 255, 0.9);
    padding: 0.3rem 0.5rem;
    border-radius: 20px;
    display: flex;
    align-items: center;
    font-size: 0.9rem;
    font-weight: 600;
    color: #1D2D44;
    z-index: 2;
}

.service-card-rating i {
    color: #FFD700;
    margin-right: 0.3rem;
}

.service-card-content {
    padding: 1.5rem;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.service-card-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin-bottom: 0.8rem;
    color: #1D2D44;
}

.service-card-description {
    font-size: 0.9rem;
    color: #748CAB;
    margin-bottom: 1rem;
    flex-grow: 1;
}

.service-card-footer {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 1rem;
    border-top: 1px solid rgba(0, 0, 0, 0.05);
}

.service-card-price {
    font-weight: 700;
    font-size: 1.1rem;
    color: #3E5C76;
}

.service-card-seller {
    display: flex;
    align-items: center;
    font-size: 0.85rem;
    color: #748CAB;
}

.service-card-seller img {
    width: 25px;
    height: 25px;
    border-radius: 50%;
    margin-right: 0.5rem;
    object-fit: cover;
}

/* Dark mode adjustments for services */
[data-bs-theme="dark"] .service-card {
    background: rgba(31, 32, 40, 0.8);
    border-color: rgba(70, 90, 120, 0.2);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

[data-bs-theme="dark"] .service-card-title {
    color: #FFFFFF;
}

[data-bs-theme="dark"] .service-card-description {
    color: #A4C2E5;
}

[data-bs-theme="dark"] .service-card-price {
    color: #8FB3DE;
}

[data-bs-theme="dark"] .service-card-rating {
    background: rgba(30, 35, 45, 0.9);
    color: #A4C2E5;
}

/* Auto-scrolling services carousel with touch/mouse scroll */
.services-scroll-container {
    position: relative;
    width: 100%;
    overflow: hidden;
    margin-top: 2rem;
    padding: 0.5rem 0;
    cursor: grab;
}

.services-scroll-container:active {
    cursor: grabbing;
}

.services-scroll-wrapper {
    display: flex;
    animation: scrollServices 40s linear infinite;
    width: max-content;
    gap: 2rem;
    transition: transform 0.3s ease;
}

.services-scroll-wrapper .service-card {
    width: 350px;
    flex: 0 0 auto;
    pointer-events: auto;
}

@keyframes scrollServices {
    0% {
        transform: translateX(0);
    }
    100% {
        transform: translateX(calc(-350px * 3 - 6rem)); /* Width of 3 cards + gaps */
    }
}

/* Timeline Styles for How It Works */
.timeline {
    position: relative;
    margin: 3rem 0;
    padding: 0;
}

.timeline:before {
    content: '';
    position: absolute;
    top: 0;
    left: 50%;
    width: 2px;
    height: 100%;
    background: linear-gradient(to bottom, rgba(93, 139, 179, 0.3), rgba(93, 139, 179, 0.7), rgba(93, 139, 179, 0.3));
    transform: translateX(-50%);
}

.timeline-item {
    position: relative;
    width: 50%;
    padding: 2rem;
    box-sizing: border-box;
    display: flex;
    opacity: 0;
    transform: translateY(30px);
    transition: all 0.8s ease-out;
}

.timeline-item.visible {
    opacity: 1;
    transform: translateY(0);
}

.timeline-item:nth-child(odd) {
    margin-left: auto;
    text-align: left;
    padding-left: 3rem;
}

.timeline-item:nth-child(even) {
    margin-right: auto;
    text-align: right;
    padding-right: 3rem;
}

.timeline-item-content {
    background: white;
    padding: 2rem;
    border-radius: 15px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    position: relative;
    border: 1px solid rgba(0, 0, 0, 0.03);
    transition: all 0.3s ease;
    width: 100%;
}

.timeline-item-content:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
}

.timeline-item:nth-child(odd) .timeline-item-content::before {
    content: '';
    position: absolute;
    top: 50%;
    left: -15px;
    width: 0;
    height: 0;
    border-top: 15px solid transparent;
    border-bottom: 15px solid transparent;
    border-right: 15px solid white;
    transform: translateY(-50%);
}

.timeline-item:nth-child(even) .timeline-item-content::before {
    content: '';
    position: absolute;
    top: 50%;
    right: -15px;
    width: 0;
    height: 0;
    border-top: 15px solid transparent;
    border-bottom: 15px solid transparent;
    border-left: 15px solid white;
    transform: translateY(-50%);
}

.timeline-item-circle {
    position: absolute;
    width: 40px;
    height: 40px;
    background: linear-gradient(135deg, #5D8BB3, #8FB3DE);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: 700;
    z-index: 2;
    box-shadow: 0 0 0 5px rgba(255, 255, 255, 0.8), 0 5px 15px rgba(0, 0, 0, 0.1);
}

.timeline-item:nth-child(odd) .timeline-item-circle {
    left: -20px;
    top: 50%;
    transform: translateY(-50%);
}

.timeline-item:nth-child(even) .timeline-item-circle {
    right: -20px;
    top: 50%;
    transform: translateY(-50%);
}

.timeline-item-title {
    font-size: 1.3rem;
    font-weight: 600;
    color: #1D2D44;
    margin-bottom: 1rem;
}

.timeline-item-description {
    color: #748CAB;
}

/* Dark mode adjustments for timeline */
[data-bs-theme="dark"] .timeline-item-content {
    background: rgba(31, 32, 40, 0.8);
    border-color: rgba(70, 90, 120, 0.2);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

[data-bs-theme="dark"] .timeline-item:nth-child(odd) .timeline-item-content::before {
    border-right-color: rgba(31, 32, 40, 0.8);
}

[data-bs-theme="dark"] .timeline-item:nth-child(even) .timeline-item-content::before {
    border-left-color: rgba(31, 32, 40, 0.8);
}

[data-bs-theme="dark"] .timeline-item-title {
    color: #FFFFFF;
}

[data-bs-theme="dark"] .timeline-item-description {
    color: #A4C2E5;
}

[data-bs-theme="dark"] .timeline-item-circle {
    background: linear-gradient(135deg, #7BA4CD, #A8C8E8);
    box-shadow: 0 0 0 5px rgba(30, 35, 45, 0.8), 0 5px 15px rgba(0, 0, 0, 0.2);
}

/* Features Grid Styles (Why Choose Us) */
.features-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 2rem;
    margin-top: 2rem;
}

.feature-card {
    background: white;
    border-radius: 15px;
    padding: 2rem;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    border: 1px solid rgba(0, 0, 0, 0.03);
    text-align: center;
    position: relative;
    overflow: hidden;
}

.feature-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    width: 4px;
    height: 100%;
    background: linear-gradient(to bottom, #5D8BB3, #8FB3DE);
    transition: all 0.3s ease;
}

.feature-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
}

.feature-card:hover::before {
    width: 100%;
    opacity: 0.1;
}

.feature-icon {
    font-size: 2.5rem;
    color: #3E5C76;
    margin-bottom: 1.5rem;
    position: relative;
    display: inline-block;
}

.feature-icon::after {
    content: '';
    position: absolute;
    width: 60px;
    height: 60px;
    background-color: rgba(93, 139, 179, 0.1);
    border-radius: 50%;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    z-index: -1;
    transition: all 0.3s ease;
}

.feature-card:hover .feature-icon::after {
    width: 70px;
    height: 70px;
    background-color: rgba(93, 139, 179, 0.2);
}

.feature-title {
    font-size: 1.3rem;
    font-weight: 600;
    color: #1D2D44;
    margin-bottom: 1rem;
}

.feature-description {
    color: #748CAB;
}

/* Dark mode adjustments for features */
[data-bs-theme="dark"] .feature-card {
    background: rgba(31, 32, 40, 0.8);
    border-color: rgba(70, 90, 120, 0.2);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
}

[data-bs-theme="dark"] .feature-card::before {
    background: linear-gradient(to bottom, #7BA4CD, #A8C8E8);
}

[data-bs-theme="dark"] .feature-title {
    color: #FFFFFF;
}

[data-bs-theme="dark"] .feature-description {
    color: #A4C2E5;
}

[data-bs-theme="dark"] .feature-icon {
    color: #8FB3DE;
}

[data-bs-theme="dark"] .feature-icon::after {
    background-color: rgba(122, 164, 205, 0.1);
}

[data-bs-theme="dark"] .feature-card:hover .feature-icon::after {
    background-color: rgba(122, 164, 205, 0.2);
}

/* Responsive adjustments for frame layout */
@media (max-width: 1200px) {
    .section-frame {
        padding: 5rem 0;
    }
    
    .section-frame-content {
        padding: 0 1.5rem;
    }
    
    .section-frame-title {
        font-size: 2.2rem;
    }
}

@media (max-width: 992px) {
    .timeline:before {
        left: 30px;
    }
    
    .timeline-item {
        width: 100%;
        padding-left: 5rem !important;
        padding-right: 1rem !important;
        text-align: left !important;
    }
    
    .timeline-item:nth-child(even) .timeline-item-content::before,
    .timeline-item:nth-child(odd) .timeline-item-content::before {
        left: -15px;
        right: auto;
        border-right: 15px solid white;
        border-left: none;
    }
    
    .timeline-item:nth-child(even) .timeline-item-circle,
    .timeline-item:nth-child(odd) .timeline-item-circle {
        left: 10px;
        right: auto;
    }
    
    [data-bs-theme="dark"] .timeline-item:nth-child(even) .timeline-item-content::before,
    [data-bs-theme="dark"] .timeline-item:nth-child(odd) .timeline-item-content::before {
        border-right-color: rgba(31, 32, 40, 0.8);
    }
    
    .service-grid,
    .features-grid {
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
    }
}

@media (max-width: 768px) {
    .section-frame {
        min-height: auto;
        padding: 5rem 0;
    }
    
    .section-frame-title {
        font-size: 2rem;
    }
    
    .section-frame-subtitle {
        font-size: 1.1rem;
    }
    
    .category-grid, 
    .service-grid, 
    .features-grid {
        grid-template-columns: 1fr;
    }
    
    .section-frame-content {
        padding: 0 1rem;
    }
    
    .timeline-item {
        padding: 1.5rem 1rem 1.5rem 5rem !important;
    }
}

@media (max-width: 480px) {
    .section-frame {
        padding: 4rem 0;
    }
    
    .section-frame-title {
        font-size: 1.8rem;
    }
    
    .section-frame-subtitle {
        font-size: 1rem;
    }
}

.project-card {
    background-color: white;
    border-radius: 12px;
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
    transition: all 0.3s ease;
    height: 100%;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    border: 1px solid rgba(0, 0, 0, 0.05);
}

[data-bs-theme="dark"] .project-card {
    background-color: var(--accent-dark);
    border-color: rgba(255, 255, 255, 0.05);
    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
}

.project-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.1);
}

[data-bs-theme="dark"] .project-card:hover {
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
}

.project-card-header {
    padding: 1.25rem 1.25rem 0.75rem;
    border-bottom: 1px solid rgba(0, 0, 0, 0.05);
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
}

[data-bs-theme="dark"] .project-card-header {
    border-bottom-color: rgba(255, 255, 255, 0.05);
}

.project-card-title {
    font-size: 1.1rem;
    font-weight: 600;
    margin: 0;
    color: var(--accent-dark);
    line-height: 1.3;
}

[data-bs-theme="dark"] .project-card-title {
    color: var(--light);
}

.project-card-badges {
    display: flex;
    flex-wrap: wrap;
    gap: 0.5rem;
}

.project-card-body {
    padding: 1.25rem;
    flex-grow: 1;
    display: flex;
    flex-direction: column;
}

.project-card-description {
    color: var(--accent);
    margin-bottom: 1rem;
    font-size: 0.9rem;
    line-height: 1.5;
}

.project-card-meta {
    margin-top: auto;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.75rem;
    font-size: 0.85rem;
    color: var(--accent);
}

.project-card-meta > div {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.project-card-meta i {
    color: var(--primary);
    font-size: 1rem;
}

[data-bs-theme="dark"] .project-card-meta i {
    color: var(--secondary);
}

.project-card-footer {
    padding: 1.25rem;
    border-top: 1px solid rgba(0, 0, 0, 0.05);
    display: flex;
    justify-content: space-between;
    gap: 0.5rem;
}

[data-bs-theme="dark"] .project-card-footer {
    border-top-color: rgba(255, 255, 255, 0.05);
}

@media (max-width: 768px) {
    .project-card-meta {
        grid-template-columns: 1fr;
    }
}

/* Notification toast styles */
.notification-toast {
    position: fixed;
    top: 20px;
    right: 20px;
    min-width: 350px;
    max-width: 90%;
    padding: 1rem 1.5rem;
    z-index: 9999;
    border-radius: 12px;
    transform: translateX(100%);
    opacity: 0;
    transition: all 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);
    box-shadow: 0 8px 20px rgba(0, 0, 0, 0.15);
    backdrop-filter: blur(10px);
}

.notification-toast.show {
    transform: translateX(0);
    opacity: 1;
}

.notification-toast.alert-success {
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.95), rgba(32, 201, 151, 0.95));
    border-left: 5px solid #28a745;
    color: #ffffff;
}

.notification-toast.alert-danger {
    background: linear-gradient(135deg, rgba(220, 53, 69, 0.95), rgba(255, 107, 107, 0.95));
    border-left: 5px solid #dc3545;
    color: #ffffff;
}

.notification-toast .d-flex {
    align-items: center;
    gap: 12px;
}

.notification-toast i {
    font-size: 1.5rem;
}

.notification-toast .btn-close {
    position: absolute;
    top: 12px;
    right: 12px;
    padding: 8px;
    background: rgba(255, 255, 255, 0.2);
    border-radius: 50%;
    margin: 0;
    opacity: 0.8;
}

@keyframes slideIn {
    from { transform: translateX(100%); opacity: 0; }
    to { transform: translateX(0); opacity: 1; }
}

@keyframes slideOut {
    from { transform: translateX(0); opacity: 1; }
    to { transform: translateX(100%); opacity: 0; }
}

/* Animation for the spinner */
@keyframes spinner {
    to { transform: rotate(360deg); }
}

.spinner-border {
    display: inline-block;
    width: 1rem;
    height: 1rem;
    border: 0.2em solid currentColor;
    border-right-color: transparent;
    border-radius: 50%;
    animation: spinner .75s linear infinite;
}

.project-card-actions {
    display: flex;
    gap: 0.5rem;
}

@media (max-width: 576px) {
    .project-card-footer {
        flex-direction: column;
        gap: 0.75rem;
    }
    
    .project-card-footer .btn {
        width: 100%;
    }
    
    .project-card-actions {
        width: 100%;
    }
}

/* Project Details Modal Styles */
.detail-item {
    margin-bottom: 12px;
}

.detail-label {
    font-weight: 600;
    color: #6c757d;
    display: inline-block;
    min-width: 100px;
}

.detail-value {
    color: #343a40;
}

.detail-section-title {
    font-weight: 600;
    margin-bottom: 10px;
    color: #343a40;
}

.detail-description {
    line-height: 1.6;
    color: #495057;
}

/* Applications History Modal Styles */
.applications-table {
    width: 100%;
    margin-top: 1rem;
    border-collapse: separate;
    border-spacing: 0;
    border-radius: 0.375rem;
    overflow: hidden;
}

.applications-table th,
.applications-table td {
    padding: 0.75rem 1rem;
    border-bottom: 1px solid rgba(0, 0, 0, 0.1);
}

.applications-table th {
    background-color: rgba(0, 0, 0, 0.02);
    font-weight: 600;
    text-align: left;
}

.applications-table tr:last-child td {
    border-bottom: none;
}

.applications-table td .badge {
    padding: 0.5em 0.75em;
}

.applications-table .btn-sm {
    padding: 0.25rem 0.5rem;
    font-size: 0.875rem;
}

.application-actions {
    display: flex;
    gap: 0.5rem;
    justify-content: flex-end;
}

[data-bs-theme="dark"] .applications-table th {
    background-color: rgba(255, 255, 255, 0.05);
}

[data-bs-theme="dark"] .applications-table th,
[data-bs-theme="dark"] .applications-table td {
    border-color: rgba(255, 255, 255, 0.1);
}

@media (max-width: 767px) {
    .applications-table {
        display: block;
        overflow-x: auto;
    }
    
    .application-actions {
        flex-direction: column;
        gap: 0.25rem;
    }
    
    .application-actions .btn {
        width: 100%;
        text-align: center;
    }
}

.empty-applications {
    padding: 2rem;
    text-align: center;
}

.empty-applications i {
    color: #dee2e6;
    font-size: 3rem;
    margin-bottom: 1rem;
    display: block;
}

/* Dark Theme Support */
[data-bs-theme="dark"] .detail-value {
    color: #e9ecef;
}

[data-bs-theme="dark"] .detail-label {
    color: #adb5bd;
}

[data-bs-theme="dark"] .detail-section-title {
    color: #e9ecef;
}

[data-bs-theme="dark"] .detail-description {
    color: #ced4da;
    background-color: #343a40 !important;
}

[data-bs-theme="dark"] .modal-content {
    background-color: #212529;
    color: #e9ecef;
}

[data-bs-theme="dark"] .modal-header {
    border-bottom-color: #495057;
}

[data-bs-theme="dark"] .modal-footer {
    border-top-color: #495057;
}

[data-bs-theme="dark"] .table {
    color: #e9ecef;
}

[data-bs-theme="dark"] .table-hover tbody tr:hover {
    background-color: rgba(255, 255, 255, 0.075);
}

[data-bs-theme="dark"] .empty-applications i {
    color: #495057;
}

.empty-applications h5 {
    font-weight: 600;
    margin-top: 1rem;
    margin-bottom: 0.5rem;
}

.empty-applications p {
    margin-bottom: 1.5rem;
}
</style>

<!-- Categories Section Frame -->
<section class="section-frame section-animate" id="categories">
    <div class="section-corner-decoration section-corner-decoration-1"></div>
    <div class="section-frame-content">
        <div class="section-frame-header">
            <h2 class="section-frame-title">Explore Popular Categories</h2>
            <p class="section-frame-subtitle">Browse through our most in-demand service categories</p>
        </div>
        <div class="category-grid">
            <a href="#" class="category-card stagger-item">
                <div class="category-icon"><i class="bi bi-code-slash"></i></div>
                <h3>Web Development</h3>
                <p>2,345 services available</p>
            </a>
            <a href="#" class="category-card stagger-item">
                <div class="category-icon"><i class="bi bi-brush"></i></div>
                <h3>Design & Creative</h3>
                <p>1,879 services available</p>
            </a>
            <a href="#" class="category-card stagger-item">
                <div class="category-icon"><i class="bi bi-megaphone"></i></div>
                <h3>Digital Marketing</h3>
                <p>1,653 services available</p>
            </a>
            <a href="#" class="category-card stagger-item">
                <div class="category-icon"><i class="bi bi-translate"></i></div>
                <h3>Writing & Translation</h3>
                <p>982 services available</p>
            </a>
            <a href="#" class="category-card stagger-item">
                <div class="category-icon"><i class="bi bi-camera-video"></i></div>
                <h3>Video & Animation</h3>
                <p>756 services available</p>
            </a>
            <a href="#" class="category-card stagger-item">
                <div class="category-icon"><i class="bi bi-graph-up"></i></div>
                <h3>Data & Analytics</h3>
                <p>543 services available</p>
            </a>
            <a href="#" class="category-card stagger-item">
                <div class="category-icon"><i class="bi bi-phone"></i></div>
                <h3>Mobile Development</h3>
                <p>897 services available</p>
            </a>
            <a href="#" class="category-card stagger-item">
                <div class="category-icon"><i class="bi bi-music-note-beamed"></i></div>
                <h3>Music & Audio</h3>
                <p>432 services available</p>
            </a>
        </div>
    </div>
    <div class="section-transition">
        <a href="#featured-services" class="section-transition-indicator">
            <i class="bi bi-chevron-down"></i>
        </a>
    </div>
    <div class="section-corner-decoration section-corner-decoration-2"></div>
</section>

<!-- Featured Services Section Frame -->
<section class="section-frame section-animate" id="featured-services">
    <div class="section-corner-decoration section-corner-decoration-1"></div>
    <div class="section-frame-content">
        <div class="section-frame-header">
            <h2 class="section-frame-title">Featured Services</h2>
            <p class="section-frame-subtitle">Discover our most popular and highly-rated freelance services</p>
        </div>
        <div class="services-scroll-container">
            <div class="services-scroll-wrapper">
                <a href="components/home/service-detail.php?id=1" class="service-card stagger-item" data-service-id="1">
                    <div class="service-card-img" style="background-image: url('https://images.unsplash.com/photo-1587440871875-191322ee64b0?auto=format&fit=crop&w=600&q=80')">
                        <div class="service-card-rating">
                            <i class="bi bi-star-fill"></i> 4.9
                        </div>
                    </div>
                    <div class="service-card-content">
                        <h3 class="service-card-title">Professional Website Development</h3>
                        <p class="service-card-description">I will create a responsive, modern website for your business or personal brand using the latest technologies.</p>
                        <div class="service-card-footer">
                            <div class="service-card-price">From $299</div>
                            <div class="service-card-seller">
                                <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Alex M.">
                                <span>Alex M.</span>
                            </div>
                        </div>
                    </div>
                </a>
                <a href="components/home/service-detail.php?id=2" class="service-card stagger-item" data-service-id="2">
                    <div class="service-card-img" style="background-image: url('https://images.unsplash.com/photo-1542744173-05336fcc7ad4?auto=format&fit=crop&w=600&q=80')">
                        <div class="service-card-rating">
                            <i class="bi bi-star-fill"></i> 4.8
                        </div>
                    </div>
                    <div class="service-card-content">
                        <h3 class="service-card-title">SEO Optimization Package</h3>
                        <p class="service-card-description">Boost your website's search engine rankings with our comprehensive SEO package including keyword research and optimization.</p>
                        <div class="service-card-footer">
                            <div class="service-card-price">From $159</div>
                            <div class="service-card-seller">
                                <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Sara K.">
                                <span>Sara K.</span>
                            </div>
                        </div>
                    </div>
                </a>
                <a href="components/home/service-detail.php?id=3" class="service-card stagger-item" data-service-id="3">
                    <div class="service-card-img" style="background-image: url('https://images.unsplash.com/photo-1545239351-ef35f43d514b?auto=format&fit=crop&w=600&q=80')">
                        <div class="service-card-rating">
                            <i class="bi bi-star-fill"></i> 5.0
                        </div>
                    </div>
                    <div class="service-card-content">
                        <h3 class="service-card-title">Brand Identity Design</h3>
                        <p class="service-card-description">Complete brand identity package including logo design, color palette, typography, and brand guidelines.</p>
                        <div class="service-card-footer">
                            <div class="service-card-price">From $349</div>
                            <div class="service-card-seller">
                                <img src="https://randomuser.me/api/portraits/men/67.jpg" alt="Marcus T.">
                                <span>Marcus T.</span>
                            </div>
                        </div>
                    </div>
                </a>
                <!-- Duplicate cards for infinite scrolling effect -->
                <a href="components/home/service-detail.php?id=1" class="service-card stagger-item" data-service-id="1">
                    <div class="service-card-img" style="background-image: url('https://images.unsplash.com/photo-1587440871875-191322ee64b0?auto=format&fit=crop&w=600&q=80')">
                        <div class="service-card-rating">
                            <i class="bi bi-star-fill"></i> 4.9
                        </div>
                    </div>
                    <div class="service-card-content">
                        <h3 class="service-card-title">Professional Website Development</h3>
                        <p class="service-card-description">I will create a responsive, modern website for your business or personal brand using the latest technologies.</p>
                        <div class="service-card-footer">
                            <div class="service-card-price">From $299</div>
                            <div class="service-card-seller">
                                <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="Alex M.">
                                <span>Alex M.</span>
                            </div>
                        </div>
                    </div>
                </a>
                <a href="components/home/service-detail.php?id=2" class="service-card stagger-item" data-service-id="2">
                    <div class="service-card-img" style="background-image: url('https://images.unsplash.com/photo-1542744173-05336fcc7ad4?auto=format&fit=crop&w=600&q=80')">
                        <div class="service-card-rating">
                            <i class="bi bi-star-fill"></i> 4.8
                        </div>
                    </div>
                    <div class="service-card-content">
                        <h3 class="service-card-title">SEO Optimization Package</h3>
                        <p class="service-card-description">Boost your website's search engine rankings with our comprehensive SEO package including keyword research and optimization.</p>
                        <div class="service-card-footer">
                            <div class="service-card-price">From $159</div>
                            <div class="service-card-seller">
                                <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="Sara K.">
                                <span>Sara K.</span>
                            </div>
                        </div>
                    </div>
                </a>
                <a href="components/home/service-detail.php?id=3" class="service-card stagger-item" data-service-id="3">
                    <div class="service-card-img" style="background-image: url('https://images.unsplash.com/photo-1545239351-ef35f43d514b?auto=format&fit=crop&w=600&q=80')">
                        <div class="service-card-rating">
                            <i class="bi bi-star-fill"></i> 5.0
                        </div>
                    </div>
                    <div class="service-card-content">
                        <h3 class="service-card-title">Brand Identity Design</h3>
                        <p class="service-card-description">Complete brand identity package including logo design, color palette, typography, and brand guidelines.</p>
                        <div class="service-card-footer">
                            <div class="service-card-price">From $349</div>
                            <div class="service-card-seller">
                                <img src="https://randomuser.me/api/portraits/men/67.jpg" alt="Marcus T.">
                                <span>Marcus T.</span>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="section-transition">
        <a href="#how-it-works" class="section-transition-indicator">
            <i class="bi bi-chevron-down"></i>
        </a>
    </div>
    <div class="section-corner-decoration section-corner-decoration-2"></div>
</section>

<!-- How It Works Section Frame -->
<section class="section-frame section-animate" id="how-it-works">
    <div class="section-corner-decoration section-corner-decoration-1"></div>
    <div class="section-frame-content">
        <div class="section-frame-header">
            <h2 class="section-frame-title">How It Works</h2>
            <p class="section-frame-subtitle">Our simple process to connect you with the perfect freelancer</p>
        </div>
        <div class="timeline">
            <div class="timeline-item section-animate">
                <div class="timeline-item-circle">1</div>
                <div class="timeline-item-content">
                    <h3 class="timeline-item-title">Find the Perfect Service</h3>
                    <p class="timeline-item-description">Browse through our categories or search for specific skills. Filter by price, delivery time, or seller rating to find exactly what you need.</p>
                </div>
            </div>
            <div class="timeline-item section-animate">
                <div class="timeline-item-circle">2</div>
                <div class="timeline-item-content">
                    <h3 class="timeline-item-title">Contact the Freelancer</h3>
                    <p class="timeline-item-description">Discuss your project details, requirements, and expectations directly with the freelancer before placing your order.</p>
                </div>
            </div>
            <div class="timeline-item section-animate">
                <div class="timeline-item-circle">3</div>
                <div class="timeline-item-content">
                    <h3 class="timeline-item-title">Place Your Order</h3>
                    <p class="timeline-item-description">Once you're satisfied with the details, place your order securely. Your payment will be held in escrow until you approve the work.</p>
                </div>
            </div>
            <div class="timeline-item section-animate">
                <div class="timeline-item-circle">4</div>
                <div class="timeline-item-content">
                    <h3 class="timeline-item-title">Receive & Review</h3>
                    <p class="timeline-item-description">Get regular updates on your project. Once delivered, review the work and provide feedback. Release payment when you're 100% satisfied.</p>
                </div>
            </div>
        </div>
    </div>
    <div class="section-transition">
        <a href="#features" class="section-transition-indicator">
            <i class="bi bi-chevron-down"></i>
        </a>
    </div>
    <div class="section-corner-decoration section-corner-decoration-2"></div>
</section>

<!-- About/Features Section Frame -->
<section class="section-frame section-animate" id="features">
    <div class="section-corner-decoration section-corner-decoration-1"></div>
    <div class="section-frame-content">
        <div class="section-frame-header">
            <h2 class="section-frame-title">Why Choose Us</h2>
            <p class="section-frame-subtitle">Discover the benefits of our freelance marketplace platform</p>
        </div>
        <div class="features-grid">
            <div class="feature-card stagger-item">
                <div class="feature-icon"><i class="bi bi-shield-check"></i></div>
                <h3 class="feature-title">Secure Payments</h3>
                <p class="feature-description">Your payments are held in escrow until you're completely satisfied with the delivered work, ensuring secure transactions.</p>
            </div>
            <div class="feature-card stagger-item">
                <div class="feature-icon"><i class="bi bi-person-check"></i></div>
                <h3 class="feature-title">Verified Freelancers</h3>
                <p class="feature-description">All our freelancers undergo a strict verification process to ensure they have the skills and experience they claim.</p>
            </div>
            <div class="feature-card stagger-item">
                <div class="feature-icon"><i class="bi bi-clock-history"></i></div>
                <h3 class="feature-title">24/7 Support</h3>
                <p class="feature-description">Our customer support team is available around the clock to assist with any questions or issues you may have.</p>
            </div>
            <div class="feature-card stagger-item">
                <div class="feature-icon"><i class="bi bi-hand-thumbs-up"></i></div>
                <h3 class="feature-title">Satisfaction Guaranteed</h3>
                <p class="feature-description">Not happy with the delivered work? Our revision policy ensures you get exactly what you need from your freelancer.</p>
            </div>
            <div class="feature-card stagger-item">
                <div class="feature-icon"><i class="bi bi-lightning-charge"></i></div>
                <h3 class="feature-title">Fast Delivery</h3>
                <p class="feature-description">Many of our services come with quick turnaround times, perfect for those urgent projects that can't wait.</p>
            </div>
            <div class="feature-card stagger-item">
                <div class="feature-icon"><i class="bi bi-currency-dollar"></i></div>
                <h3 class="feature-title">Competitive Pricing</h3>
                <p class="feature-description">Find services that fit any budget, with transparent pricing and no hidden fees or charges.</p>
            </div>
        </div>
    </div>
    <div class="section-corner-decoration section-corner-decoration-2"></div>
</section>

<!-- Projects Section -->
<section id="projects" class="section-frame">
    <div class="section-corner-decoration section-corner-decoration-1"></div>
    <div class="section-corner-decoration section-corner-decoration-2"></div>

    <div class="section-frame-content">
        <div class="section-frame-header section-animate">
            <h2 class="section-frame-title">Latest Projects</h2>
            <p class="section-frame-subtitle">Discover latest opportunities to collaborate with businesses worldwide. Find the perfect project that matches your skills and interests.</p>
        </div>

        <div class="projects-showcase section-animate" style="animation-delay: 0.2s;">
            <?php
            // Initialize ProjectModel to fetch projects
            require_once __DIR__ . '/../Dashboard/models/ProjectModel.php';
            
            try {
                // Get database connection
                require_once __DIR__ . '/../../config/database.php';
                $pdo = $GLOBALS['pdo'] ?? getDBConnection();
                
                $projectModel = new ProjectModel($pdo);
                
                // Get the latest 4 projects
                $projects = $projectModel->getAvailableProjects(null, 4);
                
                if (empty($projects)) {
                    echo '<div class="alert alert-info text-center">No projects available at the moment. Check back soon!</div>';
                } else {
                    echo '<div class="row">';
                    
                    foreach ($projects as $project) {
                        // Determine status badge class
                        $statusClass = match($project['status']) {
                            'completed' => 'bg-success',
                            'in-progress' => 'bg-warning text-dark',
                            'cancelled' => 'bg-danger',
                            default => 'bg-primary'
                        };
                        
                        // Determine priority badge
                        $priorityClass = match($project['priority']) {
                            'high' => 'text-danger',
                            'medium' => 'text-warning',
                            'low' => 'text-info',
                            default => 'text-secondary'
                        };
                        
                        // Format budget
                        $budget = !empty($project['budget']) ? '$' . number_format($project['budget'], 2) : 'Not specified';
                        
                        // Limit description length
                        $description = strlen($project['description']) > 120 ? 
                            substr($project['description'], 0, 120) . '...' : 
                            $project['description'];
                        
                        // Calculate days remaining if end date is set
                        $daysRemaining = '';
                        if (!empty($project['end_date'])) {
                            $endDate = new DateTime($project['end_date']);
                            $today = new DateTime();
                            $interval = $today->diff($endDate);
                            $daysRemaining = $interval->days;
                        }
            ?>
                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="project-card">
                                <div class="project-card-header">
                                    <h5 class="project-card-title"><?php echo htmlspecialchars($project['title']); ?></h5>
                                    <div class="project-card-badges">
                                        <span class="badge <?php echo $statusClass; ?>"><?php echo ucfirst($project['status']); ?></span>
                                    </div>
                                </div>
                                <div class="project-card-body">
                                    <p class="project-card-description"><?php echo htmlspecialchars($description); ?></p>
                                    <div class="project-card-meta">
                                        <div class="project-card-budget">
                                            <i class="bi bi-cash-stack"></i> <?php echo $budget; ?>
                                        </div>
                                        <?php if (!empty($project['client_name'])): ?>
                                        <div class="project-card-client">
                                            <i class="bi bi-person"></i> <?php echo htmlspecialchars($project['client_name']); ?>
                                        </div>
                                        <?php endif; ?>
                                        <div class="project-card-priority">
                                            <i class="bi bi-flag-fill <?php echo $priorityClass; ?>"></i> 
                                            <?php echo ucfirst($project['priority']); ?> Priority
                                        </div>
                                        <?php if (!empty($daysRemaining)): ?>
                                        <div class="project-card-deadline">
                                            <i class="bi bi-calendar-event"></i> <?php echo $daysRemaining; ?> days left
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                <div class="project-card-footer">
                                    <button type="button" class="btn btn-primary btn-sm view-details-btn" data-project-id="<?php echo $project['id']; ?>" data-bs-toggle="modal" data-bs-target="#projectDetailsModal">View Details</button>
                                    <div class="project-card-actions">
                                        <button type="button" class="btn btn-outline-secondary btn-sm apply-project-btn" data-project-id="<?php echo $project['id']; ?>" data-bs-toggle="modal" data-bs-target="#applyProjectModal">Apply Now</button>
                                    </div>
                                </div>
                            </div>
                        </div>
            <?php
                    }
                    echo '</div>';
                }
            } catch (Exception $e) {
                echo '<div class="alert alert-danger">Error loading projects: ' . htmlspecialchars($e->getMessage()) . '</div>';
            }
            ?>
            
            <div class="text-center mt-4">
                <a href="components/Dashboard/index.php?page=projects&view=available-projects" class="btn btn-primary">
                    Browse All Projects <i class="bi bi-arrow-right ms-2"></i>
                </a>
            </div>
        </div>
    </div>
    
    <div class="section-transition">
        <a href="#testimonials" class="section-transition-indicator">
            <i class="bi bi-chevron-down"></i>
        </a>
    </div>
</section>

<!-- Apply Project Modal -->
<div class="modal fade" id="applyProjectModal" tabindex="-1" aria-labelledby="applyProjectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="applyProjectModalLabel">Apply for Project</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form method="post" id="applyProjectForm" enctype="multipart/form-data">
                    <input type="hidden" name="action" value="apply_project">
                    <input type="hidden" name="project_id" value="">
                    
                    <div class="form-group mb-3">
                        <label for="message" class="form-label">Motivation Message *</label>
                        <textarea class="form-control" id="message" name="message" rows="5" 
                            placeholder="Describe your experience and why you are the best candidate..."></textarea>
                        <div class="invalid-feedback" id="messageError"></div>
                    </div>
                    
                    <div class="form-group mb-3">
                        <label for="budget_proposal" class="form-label">Proposed Budget *</label>
                        <div class="input-group">
                            <span class="input-group-text">$</span>
                            <input type="number" class="form-control" id="budget_proposal" name="budget_proposal">
                        </div>
                        <div class="form-text">Enter the amount you propose for this project.</div>
                        <div class="invalid-feedback" id="budgetError"></div>
                    </div>

                    <div class="form-group mb-3">
                        <label for="cv_file" class="form-label">Your Resume (PDF) *</label>
                        <input type="file" class="form-control" id="cv_file" name="cv_file">
                        <div class="form-text">Accepted formats: PDF, DOC, DOCX (Max 5MB)</div>
                        <div class="invalid-feedback" id="cvError"></div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">
                            <span class="spinner-border spinner-border-sm d-none" role="status" aria-hidden="true"></span>
                            Submit Application
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Project Details Modal -->
<div class="modal fade" id="projectDetailsModal" tabindex="-1" aria-labelledby="projectDetailsModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="projectDetailsModalLabel">Project Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="project-details-content">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading project details...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<!-- Applications History Modal -->
<div class="modal fade" id="applicationsHistoryModal" tabindex="-1" aria-labelledby="applicationsHistoryModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="applicationsHistoryModalLabel">My Applications</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="applications-history-content">
                    <div class="text-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading your applications...</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize section transition indicators
    const transitionIndicators = document.querySelectorAll('.section-transition-indicator');
    
    // Add code for notification display
    function showNotification(message, type) {
        // Remove existing notifications
        const existingNotifications = document.querySelectorAll('.notification-toast');
        existingNotifications.forEach(notification => {
            notification.style.animation = 'slideOut 0.3s forwards';
            setTimeout(() => notification.remove(), 300);
        });

        // Create new notification
        const notification = document.createElement('div');
        notification.className = `notification-toast alert alert-${type} alert-dismissible`;
        
        notification.innerHTML = `
            <div class="d-flex align-items-center">
                <div class="notification-icon">
                    <i class="bi ${type === 'success' ? 'bi-check-circle-fill' : 'bi-exclamation-circle-fill'}"></i>
                </div>
                <div class="notification-message">${message}</div>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
            <div class="notification-progress"></div>
        `;

        // Add notification to document
        document.body.appendChild(notification);

        // Force reflow to activate animation
        notification.offsetHeight;

        // Show notification with animation
        notification.style.transform = 'translateX(0)';
        notification.style.opacity = '1';

        // Auto-close after 5 seconds
        setTimeout(() => {
            notification.style.transform = 'translateX(100%)';
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 300);
        }, 5000);

        // Handle close button
        const closeButton = notification.querySelector('.btn-close');
        closeButton.addEventListener('click', () => {
            notification.style.transform = 'translateX(100%)';
            notification.style.opacity = '0';
            setTimeout(() => notification.remove(), 300);
        });
    }
    
    // Add event listener to Apply Project Form
    const applyProjectForm = document.getElementById('applyProjectForm');
    if (applyProjectForm) {
        applyProjectForm.addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Validation de base
            let isValid = true;
            const message = document.getElementById('message');
            const budget = document.getElementById('budget_proposal');
            const cvFile = document.getElementById('cv_file');
            
            // Réinitialiser les erreurs
            document.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
            document.getElementById('messageError').textContent = '';
            document.getElementById('budgetError').textContent = '';
            document.getElementById('cvError').textContent = '';
            
            // Valider le message
            if (!message.value.trim()) {
                message.classList.add('is-invalid');
                document.getElementById('messageError').textContent = 'Motivation message is required';
                isValid = false;
            }
            
            // Valider le budget
            if (!budget.value || parseFloat(budget.value) <= 0) {
                budget.classList.add('is-invalid');
                document.getElementById('budgetError').textContent = 'Please enter a valid budget amount';
                isValid = false;
            }
            
            // Valider le CV
            if (cvFile.files.length === 0) {
                cvFile.classList.add('is-invalid');
                document.getElementById('cvError').textContent = 'Please upload your resume';
                isValid = false;
            } else {
                const file = cvFile.files[0];
                const fileSize = file.size / 1024 / 1024; // en MB
                const allowedTypes = ['application/pdf', 'application/msword', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'];
                
                if (fileSize > 5) {
                    cvFile.classList.add('is-invalid');
                    document.getElementById('cvError').textContent = 'File size must be less than 5MB';
                    isValid = false;
                }
                
                if (!allowedTypes.includes(file.type)) {
                    cvFile.classList.add('is-invalid');
                    document.getElementById('cvError').textContent = 'Only PDF, DOC, and DOCX files are allowed';
                    isValid = false;
                }
            }
            
            if (!isValid) {
                return;
            }
            
            // Afficher l'indicateur de chargement
            const submitBtn = applyProjectForm.querySelector('button[type="submit"]');
            const spinner = submitBtn.querySelector('.spinner-border');
            submitBtn.disabled = true;
            spinner.classList.remove('d-none');
            
            // Créer un FormData pour envoyer les données y compris le fichier
            const formData = new FormData(applyProjectForm);
            
            // Soumettre le formulaire via AJAX
            fetch('components/Dashboard/index.php?page=projects&action=apply_project', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                // Réinitialiser l'état du bouton
                submitBtn.disabled = false;
                spinner.classList.add('d-none');
                
                if (data.success) {
                    // Fermer le modal
                    const applyModal = bootstrap.Modal.getInstance(document.getElementById('applyProjectModal'));
                    applyModal.hide();
                    
                    // Réinitialiser le formulaire
                    applyProjectForm.reset();
                    
                    // Afficher notification de succès
                    showNotification(data.message, 'success');
                } else {
                    // Afficher notification d'erreur
                    showNotification(data.message, 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                submitBtn.disabled = false;
                spinner.classList.add('d-none');
                showNotification('An error occurred. Please try again later.', 'danger');
            });
        });
    }

    // Function to load project details into modal
    function loadProjectDetails(projectId) {
        const detailsContent = document.querySelector('.project-details-content');
        detailsContent.innerHTML = `
            <div class="text-center">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Loading project details...</p>
            </div>
        `;

        // Fetch project details
        fetch(`components/Dashboard/index.php?page=projects&action=get_project_details&project_id=${projectId}`, {
            method: 'GET',
            headers: {
                'Accept': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const project = data.project;
                
                // Format dates
                const createdAt = new Date(project.created_at).toLocaleDateString();
                const startDate = project.start_date ? new Date(project.start_date).toLocaleDateString() : 'Not specified';
                const endDate = project.end_date ? new Date(project.end_date).toLocaleDateString() : 'Not specified';
                
                // Format priority and status
                const priorityClass = {
                    'low': 'text-info',
                    'medium': 'text-warning',
                    'high': 'text-danger'
                }[project.priority] || 'text-secondary';
                
                const statusClass = {
                    'pending': 'text-primary',
                    'in-progress': 'text-warning',
                    'completed': 'text-success',
                    'cancelled': 'text-danger'
                }[project.status] || 'text-secondary';
                
                // Build HTML for project details
                detailsContent.innerHTML = `
                    <h3 class="mb-4">${project.title}</h3>
                    
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="detail-item">
                                <span class="detail-label"><i class="bi bi-person"></i> Client:</span>
                                <span class="detail-value">${project.client_name || 'Not specified'}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label"><i class="bi bi-cash"></i> Budget:</span>
                                <span class="detail-value">$${parseFloat(project.budget).toFixed(2)}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label"><i class="bi bi-flag"></i> Priority:</span>
                                <span class="detail-value ${priorityClass}">${project.priority.charAt(0).toUpperCase() + project.priority.slice(1)}</span>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="detail-item">
                                <span class="detail-label"><i class="bi bi-calendar-event"></i> Created:</span>
                                <span class="detail-value">${createdAt}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label"><i class="bi bi-calendar-check"></i> Start Date:</span>
                                <span class="detail-value">${startDate}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label"><i class="bi bi-calendar-x"></i> End Date:</span>
                                <span class="detail-value">${endDate}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label"><i class="bi bi-check-circle"></i> Status:</span>
                                <span class="detail-value ${statusClass}">${project.status.charAt(0).toUpperCase() + project.status.slice(1)}</span>
                            </div>
                        </div>
                    </div>
                    
                    <div class="detail-section mb-4">
                        <h5 class="detail-section-title">Description</h5>
                        <div class="detail-description p-3 bg-light rounded">
                            ${project.description.replace(/\n/g, '<br>')}
                        </div>
                    </div>
                `;
                
                // Add event listener to the Apply button - now removed
                const applyButton = detailsContent.querySelector('.apply-to-project');
                if (applyButton) {
                    applyButton.addEventListener('click', function() {
                        const projectId = this.getAttribute('data-project-id');
                        const applyForm = document.getElementById('applyProjectForm');
                        if (applyForm) {
                            applyForm.querySelector('input[name="project_id"]').value = projectId;
                        }
                    });
                }
            } else {
                detailsContent.innerHTML = `
                    <div class="alert alert-danger" role="alert">
                        <i class="bi bi-exclamation-triangle-fill"></i> ${data.message || 'Failed to load project details'}
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            detailsContent.innerHTML = `
                <div class="alert alert-danger" role="alert">
                    <i class="bi bi-exclamation-triangle-fill"></i> Error loading project details. Please try again later.
                </div>
            `;
        });
    }

    // Function to load user applications into modal
    function loadUserApplications() {
        const applicationsContent = document.querySelector('.applications-history-content');
        applicationsContent.innerHTML = `
            <div class="text-center p-4">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <p class="mt-2">Loading your applications...</p>
            </div>
        `;

        // Check login status first
        fetch('components/Dashboard/index.php?check_login=1', {
            method: 'GET',
        })
        .then(response => response.json())
        .then(data => {
            if (!data.logged_in) {
                // User is not logged in, show login prompt
                applicationsContent.innerHTML = `
                    <div class="text-center p-4">
                        <div class="alert alert-warning" role="alert">
                            <i class="bi bi-person-lock me-2"></i> You need to be logged in to view your applications.
                        </div>
                        <a href="components/Login/login.php?redirect=${encodeURIComponent(window.location.href)}" class="btn btn-primary mt-3">
                            <i class="bi bi-box-arrow-in-right me-2"></i> Login to continue
                        </a>
                    </div>
                `;
                return;
            }
            
            // Fetch applications if user is logged in
            fetch('components/Dashboard/index.php?page=projects&action=get_user_candidatures', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.candidatures && data.candidatures.length > 0) {
                    // Format the applications data in a table
                    let tableHtml = `
                        <div class="table-responsive">
                            <table class="applications-table">
                                <thead>
                                    <tr>
                                        <th>Project</th>
                                        <th>Applied On</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                    `;
                    
                    data.candidatures.forEach(app => {
                        // Format status badge
                        let statusBadge = '';
                        switch(app.status) {
                            case 'pending':
                                statusBadge = '<span class="badge bg-warning">Pending</span>';
                                break;
                            case 'approved':
                                statusBadge = '<span class="badge bg-success">Approved</span>';
                                break;
                            case 'rejected':
                                statusBadge = '<span class="badge bg-danger">Rejected</span>';
                                break;
                            case 'cancelled':
                                statusBadge = '<span class="badge bg-secondary">Cancelled</span>';
                                break;
                            default:
                                statusBadge = '<span class="badge bg-info">In Review</span>';
                        }
                        
                        // Format date
                        const appDate = new Date(app.created_at);
                        const formattedDate = appDate.toLocaleDateString('en-US', { 
                            year: 'numeric', 
                            month: 'short', 
                            day: 'numeric' 
                        });
                        
                        // Build row
                        tableHtml += `
                            <tr id="candidature-${app.id}">
                                <td class="fw-medium">${app.project_title}</td>
                                <td>${formattedDate}</td>
                                <td>${statusBadge}</td>
                                <td>
                                    <div class="application-actions">
                                        <button class="btn btn-outline-primary btn-sm view-project-details" data-project-id="${app.project_id}">
                                            <i class="bi bi-eye"></i> Details
                                        </button>
                                        ${app.status === 'pending' ? 
                                            `<button class="btn btn-outline-danger btn-sm cancel-application" data-application-id="${app.id}">
                                                <i class="bi bi-x-circle"></i> Cancel
                                            </button>` : 
                                            ''
                                        }
                                    </div>
                                </td>
                            </tr>
                        `;
                    });
                    
                    tableHtml += `
                                </tbody>
                            </table>
                        </div>
                    `;
                    
                    applicationsContent.innerHTML = tableHtml;
                    
                    // Add event listeners for view details buttons
                    applicationsContent.querySelectorAll('.view-project-details').forEach(button => {
                        button.addEventListener('click', function(e) {
                            e.preventDefault();
                            const projectId = this.getAttribute('data-project-id');
                            // Close applications modal
                            const applicationsModal = bootstrap.Modal.getInstance(document.getElementById('applicationsHistoryModal'));
                            applicationsModal.hide();
                            // Open project details modal
                            loadProjectDetails(projectId);
                            const projectModal = new bootstrap.Modal(document.getElementById('projectDetailsModal'));
                            projectModal.show();
                        });
                    });
                    
                    // Add event listeners to cancel application buttons
                    applicationsContent.querySelectorAll('.cancel-application').forEach(button => {
                        button.addEventListener('click', function() {
                            if (confirm('Are you sure you want to cancel this application? This action cannot be undone.')) {
                                const applicationId = this.getAttribute('data-application-id');
                                cancelApplication(applicationId, this);
                            }
                        });
                    });
                } else {
                    // No applications found
                    applicationsContent.innerHTML = `
                        <div class="empty-applications">
                            <i class="bi bi-clipboard-x"></i>
                            <h5>No Applications Found</h5>
                            <p class="text-muted">You haven't applied to any projects yet.</p>
                            <button type="button" class="btn btn-primary mt-3" data-bs-dismiss="modal">
                                <i class="bi bi-search me-2"></i> Browse Available Projects
                            </button>
                        </div>
                    `;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                applicationsContent.innerHTML = `
                    <div class="alert alert-danger m-4" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i> Error loading your applications. Please try again later.
                    </div>
                `;
            });
        })
        .catch(error => {
            console.error('Error checking login status:', error);
            applicationsContent.innerHTML = `
                <div class="alert alert-danger m-4" role="alert">
                    <i class="bi bi-exclamation-triangle-fill me-2"></i> Error checking login status. Please try again later.
                </div>
            `;
        });
    }

    // Function to cancel an application
    function cancelApplication(applicationId, buttonElement) {
        // Create loading state
        const originalText = buttonElement.innerHTML;
        buttonElement.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Cancelling...';
        buttonElement.disabled = true;
        
        // Send cancel request
        fetch('components/Dashboard/index.php?page=projects&action=cancel_application', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: `candidature_id=${applicationId}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update the UI
                const row = buttonElement.closest('tr');
                const statusCell = row.querySelector('td:nth-child(2)');
                if (statusCell) {
                    statusCell.innerHTML = '<span class="badge bg-danger">Cancelled</span>';
                }
                // Remove the cancel button
                buttonElement.remove();
                // Show notification
                showNotification('Your application has been cancelled successfully.', 'success');
            } else {
                // Restore button
                buttonElement.innerHTML = originalText;
                buttonElement.disabled = false;
                // Show error notification
                showNotification(data.message || 'Failed to cancel application', 'danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            // Restore button
            buttonElement.innerHTML = originalText;
            buttonElement.disabled = false;
            // Show error notification
            showNotification('An error occurred. Please try again.', 'danger');
        });
    }

    // Add event listeners for project detail buttons
    document.querySelectorAll('.view-details-btn').forEach(button => {
        button.addEventListener('click', function() {
            const projectId = this.getAttribute('data-project-id');
            // Load project details
            loadProjectDetails(projectId);
        });
    });

    // Add event listeners for apply project buttons
    document.querySelectorAll('.apply-project-btn').forEach(button => {
        button.addEventListener('click', function() {
            const projectId = this.getAttribute('data-project-id');
            // Set project ID in form
            const applyForm = document.getElementById('applyProjectForm');
            if (applyForm) {
                applyForm.querySelector('input[name="project_id"]').value = projectId;
            }
        });
    });

    // Add event listener for the My Applications modal
    const applicationsModal = document.getElementById('applicationsHistoryModal');
    if (applicationsModal) {
        applicationsModal.addEventListener('show.bs.modal', function() {
            // Load user applications when the modal is opened
            loadUserApplications();
        });
    }
    
    // Make sure navbar My Applications link also loads applications
    document.querySelectorAll('.user-profile-nav a[data-bs-target="#applicationsHistoryModal"]').forEach(link => {
        link.addEventListener('click', function() {
            // Applications will be loaded by the modal show event handler
        });
    });
});
</script>
