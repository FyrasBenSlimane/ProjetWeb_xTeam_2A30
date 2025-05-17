<?php
// Applications view for clients
// This file displays all applications for a specific job
?>

<div class="container mt-4">
    <div class="row">
        <!-- Job details sidebar -->
        <div class="col-lg-4 order-lg-2 mb-4">
            <div class="card shadow-sm mb-4">
                <div class="card-header bg-white d-flex justify-content-between align-items-center">
                    <h3 class="h5 mb-0">Job Details</h3>
                    <a href="<?php echo URL_ROOT; ?>/client/editJob/<?php echo $data['job']->id; ?>" class="btn btn-sm btn-outline-primary">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                </div>
                <div class="card-body">
                    <h4 class="job-title mb-3"><?php echo htmlspecialchars($data['job']->title); ?></h4>
                    
                    <div class="job-meta mb-3">
                        <div class="badge bg-primary mb-2"><?php echo ucfirst($data['job']->job_type); ?> Price</div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-dollar-sign text-success me-2"></i>
                            <span class="fw-semibold">
                                <?php if ($data['job']->job_type == 'fixed'): ?>
                                    Budget: $<?php echo number_format($data['job']->budget, 2); ?>
                                <?php else: ?>
                                    Hourly Rate: $<?php echo number_format($data['job']->budget, 2); ?>/hr
                                <?php endif; ?>
                            </span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-layer-group text-secondary me-2"></i>
                            <span><?php echo htmlspecialchars($data['job']->category); ?></span>
                        </div>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-signal text-secondary me-2"></i>
                            <span><?php echo ucfirst($data['job']->experience_level); ?> level</span>
                        </div>
                        <?php if (!empty($data['job']->duration)): ?>
                        <div class="d-flex align-items-center mb-2">
                            <i class="fas fa-calendar-alt text-secondary me-2"></i>
                            <span>
                                <?php 
                                $duration = str_replace('_', ' ', $data['job']->duration);
                                echo ucfirst($duration);
                                ?>
                            </span>
                        </div>
                        <?php endif; ?>
                        <div class="d-flex align-items-center">
                            <i class="fas fa-clock text-secondary me-2"></i>
                            <span>Posted <?php echo date('M j, Y', strtotime($data['job']->created_at)); ?></span>
                        </div>
                    </div>
                    
                    <hr>
                    
                    <h5 class="mt-3 mb-2">Description</h5>
                    <div class="job-description mb-3">
                        <?php 
                        // Display a shortened version of the description with a "Show more" toggle
                        $description = htmlspecialchars($data['job']->description);
                        $shortDesc = (strlen($description) > 200) ? substr($description, 0, 200) . '...' : $description;
                        ?>
                        <p id="short-description" class="mb-1"><?php echo $shortDesc; ?></p>
                        <?php if (strlen($description) > 200): ?>
                        <p id="full-description" class="d-none mb-1"><?php echo $description; ?></p>
                        <a href="#" id="description-toggle" class="text-decoration-none">Show more</a>
                        <?php endif; ?>
                    </div>
                    
                    <?php if (isset($data['job']->skills) && !empty($data['job']->skills)): ?>
                    <h5 class="mt-3 mb-2">Required Skills</h5>
                    <div class="job-skills mb-3">
                        <?php 
                        $skills = json_decode($data['job']->skills, true);
                        if ($skills && is_array($skills)):
                            foreach ($skills as $skill):
                        ?>
                            <span class="badge bg-light text-dark me-2 mb-2"><?php echo htmlspecialchars($skill); ?></span>
                        <?php 
                            endforeach;
                        endif;
                        ?>
                    </div>
                    <?php endif; ?>
                    
                    <hr>
                    
                    <div class="job-actions">
                        <div class="dropdown">
                            <button class="btn btn-outline-secondary dropdown-toggle w-100" type="button" id="jobActionsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="fas fa-cog me-2"></i> Job Actions
                            </button>
                            <ul class="dropdown-menu w-100" aria-labelledby="jobActionsDropdown">
                                <li><a class="dropdown-item" href="<?php echo URL_ROOT; ?>/client/editJob/<?php echo $data['job']->id; ?>">
                                    <i class="fas fa-edit me-2"></i> Edit Job
                                </a></li>
                                <?php if ($data['job']->status === 'active'): ?>
                                <li><a class="dropdown-item text-warning job-status-toggle" href="#" data-job-id="<?php echo $data['job']->id; ?>" data-status="paused">
                                    <i class="fas fa-pause me-2"></i> Pause Job
                                </a></li>
                                <?php elseif ($data['job']->status === 'paused'): ?>
                                <li><a class="dropdown-item text-success job-status-toggle" href="#" data-job-id="<?php echo $data['job']->id; ?>" data-status="active">
                                    <i class="fas fa-play me-2"></i> Activate Job
                                </a></li>
                                <?php endif; ?>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger job-status-toggle" href="#" data-job-id="<?php echo $data['job']->id; ?>" data-status="closed">
                                    <i class="fas fa-times-circle me-2"></i> Close Job
                                </a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Need help sidebar -->
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <h3 class="h5 mb-0">Need Help?</h3>
                </div>
                <div class="card-body">
                    <p>Having trouble finding the right freelancer? Here are some tips:</p>
                    <ul class="mb-0">
                        <li>Review each application carefully</li>
                        <li>Ask for samples of previous work</li>
                        <li>Interview top candidates</li>
                        <li>Verify their skills with a test task</li>
                    </ul>
                    <div class="text-center mt-3">
                        <a href="<?php echo URL_ROOT; ?>/help/hiring" class="btn btn-outline-primary btn-sm">
                            <i class="fas fa-question-circle"></i> Hiring Guide
                        </a>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Applications list -->
        <div class="col-lg-8 order-lg-1">
            <div class="card shadow-sm">
                <div class="card-header bg-white">
                    <div class="d-flex justify-content-between align-items-center">
                        <h2 class="h4 mb-0">Applications (<?php echo count($data['applications']); ?>)</h2>
                        <a href="<?php echo URL_ROOT; ?>/client" class="btn btn-outline-secondary btn-sm">
                            <i class="fas fa-arrow-left"></i> Back to Jobs
                        </a>
                    </div>
                </div>
                
                <?php if (empty($data['applications'])): ?>
                <!-- No applications yet -->
                <div class="card-body">
                    <div class="text-center py-5">
                        <i class="fas fa-user-clock fa-3x text-muted mb-3"></i>
                        <h4>No applications yet</h4>
                        <p>Your job is still waiting for freelancers to apply. Check back soon!</p>
                        <div class="mt-4">
                            <a href="<?php echo URL_ROOT; ?>/client/editJob/<?php echo $data['job']->id; ?>" class="btn btn-outline-primary">
                                <i class="fas fa-edit me-2"></i> Edit Job to Attract More Freelancers
                            </a>
                        </div>
                    </div>
                </div>
                <?php else: ?>
                <!-- List of applications -->
                <div class="applications-list">
                    <?php foreach ($data['applications'] as $application): ?>
                    <div class="application-item p-4 border-bottom">
                        <div class="d-flex">
                            <!-- Freelancer info -->
                            <div class="freelancer-info me-3">
                                <img src="<?php echo !empty($application->profile_image) ? $application->profile_image : URL_ROOT . '/public/img/default-avatar.png'; ?>" 
                                     alt="<?php echo htmlspecialchars($application->freelancer_name); ?>" 
                                     class="rounded-circle" 
                                     style="width: 60px; height: 60px; object-fit: cover;">
                                     
                                <div class="text-center mt-2">
                                    <div class="rating">
                                        <i class="fas fa-star text-warning"></i>
                                        <span><?php echo number_format($application->avg_rating ?? 0, 1); ?></span>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Application details -->
                            <div class="application-details flex-grow-1">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h4 class="h5 mb-0">
                                            <a href="<?php echo URL_ROOT; ?>/profile/view/<?php echo $application->user_id; ?>" class="text-dark text-decoration-none">
                                                <?php echo htmlspecialchars($application->freelancer_name); ?>
                                            </a>
                                        </h4>
                                        <p class="text-muted mb-1">
                                            <?php echo htmlspecialchars($application->freelancer_title ?? 'Freelancer'); ?> • 
                                            <?php echo !empty($application->hourly_rate) ? '$' . number_format($application->hourly_rate, 2) . '/hr' : 'Rate not specified'; ?>
                                        </p>
                                        <p class="text-muted small mb-2">
                                            Applied <?php echo date('M j, Y', strtotime($application->created_at)); ?>
                                        </p>
                                    </div>
                                    <div class="application-bid">
                                        <span class="fw-bold text-success fs-5">
                                            $<?php echo number_format($application->bid_amount, 2); ?>
                                        </span>
                                        <?php if ($data['job']->job_type === 'hourly'): ?>
                                        <span class="text-muted">/hr</span>
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="application-content mb-3">
                                    <p><?php echo nl2br(htmlspecialchars($application->cover_letter)); ?></p>
                                </div>
                                
                                <div class="application-actions">
                                    <a href="<?php echo URL_ROOT; ?>/messages/create/<?php echo $application->user_id; ?>?job_id=<?php echo $data['job']->id; ?>" class="btn btn-primary btn-sm">
                                        <i class="fas fa-comments me-1"></i> Message
                                    </a>
                                    <a href="<?php echo URL_ROOT; ?>/profile/view/<?php echo $application->user_id; ?>" class="btn btn-outline-secondary btn-sm">
                                        <i class="fas fa-user me-1"></i> View Profile
                                    </a>
                                    <button type="button" class="btn btn-outline-success btn-sm hire-freelancer" data-application-id="<?php echo $application->id; ?>">
                                        <i class="fas fa-check-circle me-1"></i> Hire
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-sm decline-application" data-application-id="<?php echo $application->id; ?>">
                                        <i class="fas fa-times-circle me-1"></i> Decline
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Custom Styles -->
<style>
    /* Job meta styling */
    .job-meta {
        color: #6c757d;
    }
    
    /* Application item styling */
    .application-item {
        transition: all 0.2s ease;
    }
    
    .application-item:hover {
        background-color: #f9f9f9;
    }
    
    .application-item:last-child {
        border-bottom: none !important;
    }
    
    .application-actions {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    
    /* Button styling - Client theme */
    .btn-primary {
        background-color: #6c3494;
        border-color: #6c3494;
    }
    
    .btn-primary:hover {
        background-color: #5a2c7d;
        border-color: #5a2c7d;
    }
    
    .btn-outline-primary {
        color: #6c3494;
        border-color: #6c3494;
    }
    
    .btn-outline-primary:hover {
        background-color: #6c3494;
        border-color: #6c3494;
    }
    
    /* Card styling */
    .card {
        border: none;
        box-shadow: 0 2px 12px rgba(0, 0, 0, 0.08);
        border-radius: 8px;
    }
    
    .card-header {
        border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        padding: 1.25rem;
    }
    
    /* Badge coloring for client theme */
    .bg-primary {
        background-color: #6c3494 !important;
    }
</style>

<!-- JavaScript for interactions -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Description toggle functionality
        const descriptionToggle = document.getElementById('description-toggle');
        const shortDescription = document.getElementById('short-description');
        const fullDescription = document.getElementById('full-description');
        
        if (descriptionToggle && shortDescription && fullDescription) {
            descriptionToggle.addEventListener('click', function(e) {
                e.preventDefault();
                
                if (fullDescription.classList.contains('d-none')) {
                    // Show full description
                    shortDescription.classList.add('d-none');
                    fullDescription.classList.remove('d-none');
                    descriptionToggle.textContent = 'Show less';
                } else {
                    // Show short description
                    shortDescription.classList.remove('d-none');
                    fullDescription.classList.add('d-none');
                    descriptionToggle.textContent = 'Show more';
                }
            });
        }
        
        // Job status toggle functionality
        const statusToggles = document.querySelectorAll('.job-status-toggle');
        if (statusToggles) {
            statusToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    
                    const jobId = this.getAttribute('data-job-id');
                    const newStatus = this.getAttribute('data-status');
                    
                    if (confirm(`Are you sure you want to change this job's status to ${newStatus}?`)) {
                        // Send AJAX request to update job status
                        fetch(`<?php echo URL_ROOT; ?>/client/changeJobStatus`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `job_id=${jobId}&status=${newStatus}`
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Reload the page to show updated status
                                window.location.reload();
                            } else {
                                alert('Failed to update job status. Please try again.');
                            }
                        })
                        .catch(error => {
                            console.error('Error updating job status:', error);
                            alert('An error occurred. Please try again.');
                        });
                    }
                });
            });
        }
        
        // Hire freelancer functionality
        const hireButtons = document.querySelectorAll('.hire-freelancer');
        if (hireButtons) {
            hireButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const applicationId = this.getAttribute('data-application-id');
                    
                    if (confirm('Are you sure you want to hire this freelancer?')) {
                        // Send AJAX request to hire freelancer
                        fetch(`<?php echo URL_ROOT; ?>/client/hireFreelancer`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `application_id=${applicationId}&job_id=<?php echo $data['job']->id; ?>`
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert('Freelancer hired successfully! You can now message them to get started.');
                                // Redirect to the messaging page
                                window.location.href = `<?php echo URL_ROOT; ?>/messages/view/${data.conversation_id}`;
                            } else {
                                alert(data.message || 'Failed to hire freelancer. Please try again.');
                            }
                        })
                        .catch(error => {
                            console.error('Error hiring freelancer:', error);
                            alert('An error occurred. Please try again.');
                        });
                    }
                });
            });
        }
        
        // Decline application functionality
        const declineButtons = document.querySelectorAll('.decline-application');
        if (declineButtons) {
            declineButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const applicationId = this.getAttribute('data-application-id');
                    
                    if (confirm('Are you sure you want to decline this application?')) {
                        // Send AJAX request to decline application
                        fetch(`<?php echo URL_ROOT; ?>/client/declineApplication`, {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                            },
                            body: `application_id=${applicationId}&job_id=<?php echo $data['job']->id; ?>`
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                // Remove the application from the list
                                this.closest('.application-item').remove();
                                
                                // Update the application count
                                const applicationCount = document.querySelector('.card-header h2');
                                const currentCount = parseInt(applicationCount.textContent.match(/\d+/)[0]);
                                applicationCount.textContent = `Applications (${currentCount - 1})`;
                                
                                // Show empty state if no more applications
                                if (currentCount - 1 === 0) {
                                    const applicationsListDiv = document.querySelector('.applications-list');
                                    applicationsListDiv.innerHTML = `
                                        <div class="card-body">
                                            <div class="text-center py-5">
                                                <i class="fas fa-user-clock fa-3x text-muted mb-3"></i>
                                                <h4>No applications yet</h4>
                                                <p>Your job is still waiting for freelancers to apply. Check back soon!</p>
                                                <div class="mt-4">
                                                    <a href="<?php echo URL_ROOT; ?>/client/editJob/<?php echo $data['job']->id; ?>" class="btn btn-outline-primary">
                                                        <i class="fas fa-edit me-2"></i> Edit Job to Attract More Freelancers
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    `;
                                }
                            } else {
                                alert(data.message || 'Failed to decline application. Please try again.');
                            }
                        })
                        .catch(error => {
                            console.error('Error declining application:', error);
                            alert('An error occurred. Please try again.');
                        });
                    }
                });
            });
        }
    });
</script>

<?php
// This view includes the header and footer via the controller
?> 