<?php
/**
 * Job item template - used for displaying a single job in a list
 * 
 * Expected variables:
 * - $job: Job object with all job properties
 */
?>
<div class="job-list-item">
    <div class="job-card simplified-card" data-job-id="<?php echo $job->id; ?>">
        <div class="job-icon">
            <i class="fas fa-briefcase"></i>
        </div>
        <h3 class="job-title"><?php echo htmlspecialchars($job->title); ?></h3>
        <span class="job-status-dot status-<?php echo $job->status; ?>"></span>
    </div>
</div>

<style>
    /* Simplified Job Card Styles */
    .job-list-item {
        margin-bottom: 1.5rem;
        height: 150px;
    }
    
    .simplified-card {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        background: #ffffff;
        cursor: pointer;
        transition: all 0.2s ease-in-out;
        position: relative; /* Added for better click handling */
        z-index: 1; /* Ensure it's above other elements */
        border: 1px solid rgba(0, 0, 0, 0.08);
        border-radius: 12px;
        padding: 1.5rem;
        height: 100%;
        width: 100%;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.04);
        transition: all 0.3s ease;
        cursor: pointer;
        position: relative;
        overflow: hidden;
        text-align: center;
    }
    
    .simplified-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1);
    }
    
    .simplified-card:active {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.08);
    }
    
    /* Add a subtle indication that the card is clickable */
    .simplified-card::after {
        content: '';
        position: absolute;
        top: 10px;
        right: 10px;
        width: 8px;
        height: 8px;
        border-top: 2px solid rgba(0,0,0,0.2);
        border-right: 2px solid rgba(0,0,0,0.2);
        transform: rotate(45deg);
        opacity: 0.5;
        transition: opacity 0.2s ease;
    }
    
    .simplified-card:hover::after {
        opacity: 0.8;
        border-color: rgba(0, 0, 0, 0.12);
    }
    
    .simplified-card:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 3px;
        height: 100%;
        background-color: var(--primary-color);
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .simplified-card:hover:before {
        opacity: 1;
    }
    
    .simplified-card .job-icon {
        width: 52px;
        height: 52px;
        background-color: rgba(44, 62, 80, 0.08);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 0 0.875rem 0;
        color: var(--primary-color);
        flex-shrink: 0;
        font-size: 20px;
        transition: all 0.3s ease;
    }
    
    .simplified-card:hover .job-icon {
        background-color: rgba(44, 62, 80, 0.12);
        transform: scale(1.05);
    }
    
    .simplified-card .job-title {
        font-size: 1.125rem;
        font-weight: 700;
        color: #2c3e50;
        margin: 0;
        line-height: 1.3;
        letter-spacing: -0.01em;
        text-align: center;
        max-width: 100%;
        overflow: hidden;
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }
    
    .job-status-dot {
        position: absolute;
        top: 12px;
        right: 12px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
    }
    
    .status-active {
        background-color: #2ecc71;
    }
    
    .status-paused {
        background-color: #f5a623;
    }
    
    .status-closed {
        background-color: #95a5a6;
    }
    
    /* Job Details Slider */
    .job-details-slider {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) scale(0.9);
        width: 90%;
        max-width: 800px;
        height: 80vh;
        background: #fff;
        border-radius: 12px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
        z-index: 1001;
        overflow: hidden;
        display: flex;
        flex-direction: column;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }
    
    .job-details-slider.active {
        opacity: 1;
        visibility: visible;
        transform: translate(-50%, -50%) scale(1);
    }
    
    .job-details-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1000;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
        backdrop-filter: blur(2px);
    }
    
    .job-details-overlay.active {
        opacity: 1;
        visibility: visible;
    }
    
    .job-details-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 1.25rem 1.5rem;
        border-bottom: 1px solid rgba(0, 0, 0, 0.08);
    }
    
    .job-details-title {
        font-size: 1.25rem;
        font-weight: 600;
        margin: 0;
    }
    
    .job-details-close {
        width: 32px;
        height: 32px;
        background: rgba(0, 0, 0, 0.05);
        border: none;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s ease;
    }
    
    .job-details-close:hover {
        background: rgba(0, 0, 0, 0.1);
    }
    
    .job-details-content {
        flex: 1;
        padding: 1.5rem;
        overflow-y: auto;
    }
    
    .job-details-footer {
        padding: 1rem 1.5rem;
        border-top: 1px solid rgba(0, 0, 0, 0.08);
        display: flex;
        justify-content: flex-end;
        gap: 1rem;
    }
    
    @media (max-width: 768px) {
        .job-details-slider {
            width: 100%;
            height: 100vh;
            border-radius: 0;
            max-width: none;
        }
    }
    
    /* Job Actions */
    .job-actions {
        position: absolute;
        bottom: 10px;
        right: 10px;
        display: flex;
        gap: 5px;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .simplified-card:hover .job-actions {
        opacity: 1;
    }
    
    .job-actions .btn {
        width: 30px;
        height: 30px;
        padding: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        font-size: 0.75rem;
    }
</style>