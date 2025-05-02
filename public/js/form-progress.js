/**
 * Form Progress Indicator
 * Shows a visual progress bar for form completion
 */

document.addEventListener('DOMContentLoaded', function() {
    // Only run on create topic page
    const topicForm = document.querySelector('form[action*="createTopic"]');
    if (!topicForm) return;
    
    // Create progress bar container
    const progressContainer = document.createElement('div');
    progressContainer.className = 'form-progress-container mb-4';
    progressContainer.innerHTML = `
        <div class="progress" style="height: 8px;">
            <div class="progress-bar" role="progressbar" style="width: 0%;" 
                aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
        <div class="d-flex justify-content-between mt-1">
            <small class="text-muted">Form completion</small>
            <small class="text-muted progress-percentage">0%</small>
        </div>
    `;
    
    // Insert progress bar after the form header
    const cardHeader = document.querySelector('.card-header');
    if (cardHeader) {
        cardHeader.parentNode.insertBefore(progressContainer, cardHeader.nextSibling);
    }
    
    // Get form elements
    const titleInput = document.getElementById('title');
    const categorySelect = document.getElementById('category_id');
    const contentTextarea = document.getElementById('content');
    
    // Get progress elements
    const progressBar = document.querySelector('.progress-bar');
    const progressPercentage = document.querySelector('.progress-percentage');
    
    // Update progress function
    function updateProgress() {
        // Calculate progress based on field completion
        let progress = 0;
        let fieldsCompleted = 0;
        const totalFields = 3; // title, category, content
        
        // Check title
        if (titleInput.value.trim().length >= 5) {
            fieldsCompleted++;
        }
        
        // Check category
        if (categorySelect.value) {
            fieldsCompleted++;
        }
        
        // Check content
        if (contentTextarea.value.trim().length >= 20) {
            fieldsCompleted++;
        }
        
        // Calculate percentage
        progress = Math.round((fieldsCompleted / totalFields) * 100);
        
        // Update progress bar
        progressBar.style.width = `${progress}%`;
        progressBar.setAttribute('aria-valuenow', progress);
        progressPercentage.textContent = `${progress}%`;
        
        // Update progress bar color
        progressBar.className = 'progress-bar';
        if (progress === 100) {
            progressBar.classList.add('bg-success');
        } else if (progress >= 66) {
            progressBar.classList.add('bg-info');
        } else if (progress >= 33) {
            progressBar.classList.add('bg-warning');
        } else {
            progressBar.classList.add('bg-danger');
        }
    }
    
    // Add event listeners
    titleInput.addEventListener('input', updateProgress);
    categorySelect.addEventListener('change', updateProgress);
    contentTextarea.addEventListener('input', updateProgress);
    
    // Initial progress update
    updateProgress();
});