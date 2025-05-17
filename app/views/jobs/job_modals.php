<?php
/**
 * Job Modals
 * 
 * This file contains all modals used in the jobs section:
 * - Post Job Modal
 * - Job Details Modal
 * - Edit Job Modal
 * - View Applications Modal
 * - Delete Confirmation Modal
 */
?>

<!-- Post Job Modal -->
<div id="postJobModal" class="modal-overlay">
    <div class="modal-container modal-large">
        <div class="modal-header">
            <h3 class="modal-title">Post a New Job</h3>
            <button class="modal-close" onclick="closeModal('postJobModal')">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <form action="<?php echo URL_ROOT; ?>/client/postJobAjax" method="POST" id="postJobForm">
                <div class="modal-form-group">
                    <label for="title" class="modal-label">Job Title</label>
                    <input type="text" class="modal-input" id="title" name="title" data-required="true" placeholder="e.g. WordPress Website Development">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="modal-form-group">
                    <label for="category" class="modal-label">Category</label>
                    <select class="modal-input" id="category" name="category" data-required="true">
                        <option value="" selected disabled>Select a category</option>
                        <option value="web_development">Web Development</option>
                        <option value="mobile_development">Mobile Development</option>
                        <option value="design">Design</option>
                        <option value="writing">Writing & Translation</option>
                        <option value="marketing">Marketing</option>
                        <option value="video">Video & Animation</option>
                        <option value="audio">Music & Audio</option>
                        <option value="business">Business</option>
                        <option value="data_science">Data Science & Analytics</option>
                        <option value="other">Other</option>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="modal-form-grid">
                    <div class="modal-form-group">
                        <label for="budget" class="modal-label">Budget ($)</label>
                        <input type="number" class="modal-input" id="budget" name="budget" data-required="true" data-min="5" placeholder="e.g. 500">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="modal-form-group">
                        <label for="duration" class="modal-label">Expected Duration</label>
                        <select class="modal-input" id="duration" name="duration" data-required="true">
                            <option value="" selected disabled>Select duration</option>
                            <option value="less_than_1_week">Less than 1 week</option>
                            <option value="1_to_2_weeks">1-2 weeks</option>
                            <option value="3_to_4_weeks">3-4 weeks</option>
                            <option value="1_to_3_months">1-3 months</option>
                            <option value="3_to_6_months">3-6 months</option>
                            <option value="more_than_6_months">More than 6 months</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-form-group">
                    <label for="skills" class="modal-label">Required Skills</label>
                    <input type="text" class="modal-input" id="skills" name="skills" placeholder="e.g. PHP, MySQL, JavaScript">
                    <div class="modal-input-helper">Separate skills with commas</div>
                </div>
                <div class="modal-form-group">
                    <label for="description" class="modal-label">Job Description</label>
                    <textarea class="modal-input" id="description" name="description" rows="5" data-required="true" placeholder="Describe your project in detail..."></textarea>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="modal-form-group">
                    <label class="modal-label">Experience Level</label>
                    <div class="modal-checkbox-group">
                        <label class="modal-checkbox">
                            <input type="radio" name="experience_level" id="entry" value="entry" checked>
                            <span>Entry Level</span>
                        </label>
                        <label class="modal-checkbox">
                            <input type="radio" name="experience_level" id="intermediate" value="intermediate">
                            <span>Intermediate</span>
                        </label>
                        <label class="modal-checkbox">
                            <input type="radio" name="experience_level" id="expert" value="expert">
                            <span>Expert</span>
                        </label>
                    </div>
                </div>
                <!-- Hidden job_type field - set to 'fixed' by default -->
                <input type="hidden" name="job_type" value="fixed">
            </form>
        </div>
        <div class="modal-footer">
            <button class="modal-button modal-button-secondary" onclick="closeModal('postJobModal')">Cancel</button>
            <button type="submit" form="postJobForm" class="modal-button modal-button-primary">Post Job</button>
        </div>
    </div>
</div>

<!-- Job Details Modal -->
<div id="jobDetailsModal" class="modal-overlay">
    <div class="modal-container modal-large">
        <div class="modal-header">
            <h3 class="modal-title">Job Details</h3>
            <button class="modal-close" onclick="closeModal('jobDetailsModal')">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="job-details-content">
                <!-- Content will be loaded dynamically via JavaScript -->
                <div class="placeholder-content">
                    <div class="text-center py-5">
                        <div class="spinner">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading job details...</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="modal-button modal-button-secondary" onclick="closeModal('jobDetailsModal')">Close</button>
            <button class="modal-button modal-button-primary edit-job-btn" data-job-id="" onclick="editJob(this.getAttribute('data-job-id'))">Edit Job</button>
        </div>
    </div>
</div>

<!-- Edit Job Modal -->
<div id="editJobModal" class="modal-overlay">
    <div class="modal-container modal-large">
        <div class="modal-header">
            <h3 class="modal-title">Edit Job</h3>
            <button class="modal-close" onclick="closeModal('editJobModal')">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <form action="<?php echo URL_ROOT; ?>/jobs/update" method="POST" id="editJobForm">
                <input type="hidden" id="edit_job_id" name="job_id" value="">
                <div class="modal-form-group">
                    <label for="edit_title" class="modal-label">Job Title</label>
                    <input type="text" class="modal-input" id="edit_title" name="title" data-required="true">
                    <div class="invalid-feedback"></div>
                </div>
                <div class="modal-form-group">
                    <label for="edit_category" class="modal-label">Category</label>
                    <select class="modal-input" id="edit_category" name="category" data-required="true">
                        <option value="web_development">Web Development</option>
                        <option value="mobile_development">Mobile Development</option>
                        <option value="design">Design</option>
                        <option value="writing">Writing & Translation</option>
                        <option value="marketing">Marketing</option>
                        <option value="video">Video & Animation</option>
                        <option value="audio">Music & Audio</option>
                        <option value="business">Business</option>
                        <option value="data_science">Data Science & Analytics</option>
                        <option value="other">Other</option>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="modal-form-grid">
                    <div class="modal-form-group">
                        <label for="edit_budget" class="modal-label">Budget ($)</label>
                        <input type="number" class="modal-input" id="edit_budget" name="budget" data-required="true" data-min="5">
                        <div class="invalid-feedback"></div>
                    </div>
                    <div class="modal-form-group">
                        <label for="edit_duration" class="modal-label">Expected Duration</label>
                        <select class="modal-input" id="edit_duration" name="duration" data-required="true">
                            <option value="less_than_1_week">Less than 1 week</option>
                            <option value="1_to_2_weeks">1-2 weeks</option>
                            <option value="3_to_4_weeks">3-4 weeks</option>
                            <option value="1_to_3_months">1-3 months</option>
                            <option value="3_to_6_months">3-6 months</option>
                            <option value="more_than_6_months">More than 6 months</option>
                        </select>
                        <div class="invalid-feedback"></div>
                    </div>
                </div>
                <div class="modal-form-group">
                    <label for="edit_skills" class="modal-label">Required Skills</label>
                    <input type="text" class="modal-input" id="edit_skills" name="skills">
                    <div class="modal-input-helper">Separate skills with commas</div>
                </div>
                <div class="modal-form-group">
                    <label for="edit_description" class="modal-label">Job Description</label>
                    <textarea class="modal-input" id="edit_description" name="description" rows="5" data-required="true"></textarea>
                    <div class="invalid-feedback"></div>
                </div>
                <div class="modal-form-group">
                    <label class="modal-label">Experience Level</label>
                    <div class="modal-checkbox-group">
                        <label class="modal-checkbox">
                            <input type="radio" name="experience_level" id="edit_entry" value="entry">
                            <span>Entry Level</span>
                        </label>
                        <label class="modal-checkbox">
                            <input type="radio" name="experience_level" id="edit_intermediate" value="intermediate">
                            <span>Intermediate</span>
                        </label>
                        <label class="modal-checkbox">
                            <input type="radio" name="experience_level" id="edit_expert" value="expert">
                            <span>Expert</span>
                        </label>
                    </div>
                </div>
                <div class="modal-form-group">
                    <label for="edit_status" class="modal-label">Job Status</label>
                    <select class="modal-input" id="edit_status" name="status" data-required="true">
                        <option value="active">Active</option>
                        <option value="paused">Paused</option>
                        <option value="closed">Closed</option>
                    </select>
                    <div class="invalid-feedback"></div>
                </div>
                <!-- Hidden job_type field - set to 'fixed' by default -->
                <input type="hidden" name="job_type" value="fixed">
            </form>
        </div>
        <div class="modal-footer">
            <button class="modal-button modal-button-secondary" onclick="closeModal('editJobModal')">Cancel</button>
            <button type="submit" form="editJobForm" class="modal-button modal-button-primary">Save Changes</button>
        </div>
    </div>
</div>

<!-- View Applications Modal -->
<div id="viewApplicationsModal" class="modal-overlay">
    <div class="modal-container modal-large">
        <div class="modal-header">
            <h3 class="modal-title">Applications for <span class="job-title-placeholder"></span></h3>
            <button class="modal-close" onclick="closeModal('viewApplicationsModal')">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <div class="applications-container">
                <!-- Content will be loaded dynamically via JavaScript -->
                <div class="placeholder-content">
                    <div class="text-center py-5">
                        <div class="spinner">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-2">Loading applications...</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-footer">
            <button class="modal-button modal-button-secondary" onclick="closeModal('viewApplicationsModal')">Close</button>
        </div>
    </div>
</div>

<!-- Delete Confirmation Modal -->
<div id="deleteJobModal" class="modal-overlay">
    <div class="modal-container modal-small">
        <div class="modal-header">
            <h3 class="modal-title">Confirm Delete</h3>
            <button class="modal-close" onclick="closeModal('deleteJobModal')">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>
        <div class="modal-body">
            <p>Are you sure you want to delete this job posting?</p>
            <p class="modal-text-danger"><strong>Warning:</strong> This action cannot be undone. All applications associated with this job will also be deleted.</p>
            <p class="job-title-to-delete fw-bold"></p>
            <form action="<?php echo URL_ROOT; ?>/jobs/delete" method="POST" id="deleteJobForm">
                <input type="hidden" id="delete_job_id" name="job_id" value="">
            </form>
        </div>
        <div class="modal-footer">
            <button class="modal-button modal-button-secondary" onclick="closeModal('deleteJobModal')">Cancel</button>
            <button type="submit" form="deleteJobForm" class="modal-button modal-button-danger">Delete Job</button>
        </div>
    </div>
</div>

<!-- Modal Styles -->
<style>    /* Modal container improvements */
    .modal-overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 9999; /* Higher z-index to ensure it shows above other elements */
        align-items: center;
        justify-content: center;
        padding: 20px;
        transition: opacity 0.3s ease;
        opacity: 0;
        visibility: hidden;
    }
    
    .modal-overlay.active {
        display: flex !important; /* Force display even if other CSS tries to hide it */
        opacity: 1;
        visibility: visible;
    }
    
    .modal-container {
        max-width: 600px; /* Default size */
        background-color: #fff;
        border-radius: 16px;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.12);
        margin: 0 auto;
        position: relative;
        width: 90%;
        max-height: 85vh;
        overflow: hidden; /* Control overflow in child elements */
        transition: transform 0.2s ease, opacity 0.2s ease;
        opacity: 1;
        display: flex;
        flex-direction: column;
        transform: translateY(20px);
        border: 1px solid #f3f4f6;
    }
    
    .modal-overlay.active .modal-container {
        transform: translateY(0);
    }
    
    .modal-container.modal-large {
        max-width: 800px;
        width: 92%;
    }
    
    .modal-container.modal-small {
        max-width: 450px;
    }
    
        /* Modal header improvements */    .modal-header {        padding: 20px 24px;        border-bottom: 1px solid #f3f4f6;        position: sticky;        top: 0;        background-color: #fff;        z-index: 10;        border-radius: 16px 16px 0 0;        display: flex;        align-items: center;        justify-content: space-between;    }        .modal-title {        font-size: 20px;        font-weight: 600;        color: #111827;        margin: 0;        line-height: 1.3;    }
    
    .modal-close {
        background: none;
        border: none;
        color: #6b7280;
        transition: all 0.15s ease;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        cursor: pointer;
        margin-left: 8px;
    }
    
    .modal-close:hover {
        background-color: #f3f4f6;
        color: #111827;
        transform: rotate(90deg);
    }
    
    .modal-close svg {
        width: 20px;
        height: 20px;
        stroke-width: 2px;
    }
    
    /* Modal body improvements */
    .modal-body {
        padding: 24px;
        overflow-y: auto;
        flex: 1 1 auto;
        background-color: #ffffff;
    }
    
    /* Job details styling */
    .job-details {
        padding: 0;
        background-color: #ffffff;
        border-radius: 10px;
    }
    
    .job-details-header {
        margin-bottom: 28px;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 16px;
    }
    
    .job-details h3 {
        font-size: 26px;
        font-weight: 700;
        margin-bottom: 12px;
        color: #111827;
        line-height: 1.2;
    }
    
    /* Shadcn UI inspired card layout */
    .job-detail-card {
        background-color: #ffffff;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 24px;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05), 0 1px 2px rgba(0, 0, 0, 0.04);
        border: 1px solid #f3f4f6;
        transition: all 0.2s ease;
    }
    
    .job-detail-card:hover {
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05), 0 10px 15px rgba(0, 0, 0, 0.03);
    }
    
    /* Section title styling */
    .detail-section-title {
        font-size: 16px;
        font-weight: 600;
        color: #4b5563;
        margin-bottom: 12px;
        padding-bottom: 8px;
        border-bottom: 1px solid #f3f4f6;
        letter-spacing: 0.01em;
    }
    
    /* Badge improvements */
    .job-details-header .badge-container {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-bottom: 16px;
    }
    
    .job-details-header .badge {
        font-size: 14px;
        padding: 6px 12px;
        font-weight: 500;
        border-radius: 6px;
        display: inline-flex;
        align-items: center;
        background-color: #f9fafb;
        color: #374151;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
        border: 1px solid rgba(0, 0, 0, 0.05);
    }
    
    .job-details-header .badge i,
    .job-details-header .badge svg {
        margin-right: 6px;
        font-size: 14px;
        color: #6b7280;
    }
    
    /* Status badge styling */
    .status-wrapper {
        padding: 0;
        border-radius: 6px;
        display: inline-block;
    }
    
    .status-wrapper .badge {
        font-size: 14px;
        padding: 6px 12px;
        font-weight: 500;
        border-radius: 6px;
        text-transform: capitalize;
    }
    
    .badge.bg-success {
        background-color: #10b981 !important;
        color: white;
        border: none;
    }
    
    .badge.bg-warning {
        background-color: #f59e0b !important;
        color: #1f2937;
        border: none;
    }
    
    .badge.bg-secondary {
        background-color: #6b7280 !important;
        color: white;
        border: none;
    }
    
    /* Grid layout for meta information */
    .job-meta-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 24px;
    }
    
    .job-meta-item {
        display: flex;
        flex-direction: column;
        gap: 4px;
    }
    
    .job-meta-label {
        font-size: 14px;
        color: #6b7280;
        font-weight: 500;
    }
    
    .job-meta-value {
        font-size: 16px;
        color: #111827;
        font-weight: 600;
    }
    
    /* Section improvements */
    .job-details-body {
        padding: 0;
        margin-bottom: 24px;
    }
    
    .job-details-body h5 {
        font-size: 18px;
        font-weight: 600;
        margin-bottom: 16px;
        color: #111827;
        margin-top: 24px;
    }
    
    .job-details-body .editable-content {
        line-height: 1.7;
        color: #1f2937;
        font-size: 15px;
    }
    
    /* Description section */
    .job-description {
        background-color: #ffffff;
        padding: 0;
        margin-bottom: 24px;
        line-height: 1.7;
    }
    
    /* Skills styling */
    .skills-list {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
        margin-top: 12px;
    }
    
    .skills-list .badge {
        background-color: #f3f4f6;
        color: #4b5563;
        font-size: 14px;
        font-weight: 500;
        padding: 6px 12px;
        border-radius: 6px;
        margin: 0;
        border: 1px solid #e5e7eb;
        transition: all 0.2s ease;
    }
    
    .skills-list .badge:hover {
        background-color: #e5e7eb;
        color: #1f2937;
    }
    
    /* Footer improvements */
    .job-details-footer {
        padding-top: 20px;
        border-top: 1px solid #e5e7eb;
        margin-top: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 16px;
    }
    
    .job-meta {
        color: #6b7280;
        font-size: 14px;
    }
    
    .job-meta div {
        margin-bottom: 8px;
        display: flex;
        align-items: center;
    }
    
    .job-meta i,
    .job-meta svg {
        color: #6b7280;
        width: 20px;
        margin-right: 8px;
    }
    
    .job-actions {
        display: flex;
        gap: 10px;
    }
    
    /* Action buttons in job details */
    .action-button {
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s ease;
        background-color: #ffffff;
        border: 1px solid #e5e7eb;
        color: #4b5563;
        cursor: pointer;
    }
    
    .action-button:hover {
        background-color: #f9fafb;
        color: #111827;
        border-color: #d1d5db;
    }
    
    .action-button-primary {
        background-color: #2563eb;
        color: white;
        border: none;
    }
    
    .action-button-primary:hover {
        background-color: #1d4ed8;
        color: white;
    }
    
    /* Editable fields styling */
    .editable-field {
        position: relative;
        padding: 10px;
        border-radius: 8px;
        transition: all 0.2s ease;
        margin-bottom: 16px;
    }
    
    .editable-field:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }
    
    .editable-field .edit-field-btn {
        position: absolute;
        right: 8px;
        top: 8px;
        background: #ffffff;
        border: 1px solid #e5e7eb;
        font-size: 13px;
        color: #6b7280;
        opacity: 0;
        transition: opacity 0.2s ease, background-color 0.2s ease;
        cursor: pointer;
        padding: 4px 8px;
        border-radius: 6px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
    }
    
    .editable-field:hover .edit-field-btn {
        opacity: 1;
    }
    
    .editable-field .edit-field-btn:hover {
        color: #111827;
        background-color: #f3f4f6;
    }
    
    /* Editing state styling */
    .editable-field.editing {
        background-color: #f9fafb;
        padding: 16px;
        border: 1px solid #e5e7eb;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    }
    
    .editable-field .edit-form {
        margin-top: 12px;
    }
    
    .editable-field .field-actions {
        display: flex;
        justify-content: flex-end;
        gap: 8px;
        margin-top: 16px;
    }
    
    /* Application cards */
    .application-card {
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 24px;
        margin-bottom: 20px;
        transition: all 0.2s ease;
        background-color: #fff;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    
    .application-card:hover {
        box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        transform: translateY(-2px);
    }
    
    .application-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
    }
    
    .applicant-info {
        display: flex;
        align-items: center;
        gap: 16px;
    }
    
    .applicant-avatar {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #fff;
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    
    .applicant-name {
        font-size: 18px;
        font-weight: 600;
        margin: 0 0 6px 0;
        color: #2c3e50;
    }
    
    .applicant-headline {
        font-size: 14px;
        color: #495057;
        margin-bottom: 4px;
    }
    
    .applicant-location {
        font-size: 14px;
        color: #6c757d;
        display: flex;
        align-items: center;
    }
    
    .applicant-location::before {
        content: "📍";
        margin-right: 6px;
        font-size: 12px;
    }
    
    .application-date {
        font-size: 13px;
        color: #6c757d;
        padding: 6px 10px;
        background-color: #f8f9fa;
        border-radius: 4px;
        white-space: nowrap;
    }
    
    .application-content {
        margin-bottom: 20px;
        font-size: 15px;
        line-height: 1.6;
        color: #2c3e50;
        padding: 0 4px;
    }
    
    .application-footer {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-top: 20px;
        border-top: 1px solid #e9ecef;
    }
    
    .attachment-preview {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        color: #2c3e50;
        padding: 8px 14px;
        border-radius: 6px;
        background-color: #f5f7fa;
        margin-right: 10px;
        margin-bottom: 6px;
        transition: background-color 0.15s ease;
    }
    
    .attachment-preview:hover {
        background-color: #e9ecef;
    }
    
    .application-actions {
        display: flex;
        gap: 10px;
    }
    
    .application-actions button {
        padding: 8px 16px;
        border-radius: 6px;
        font-size: 14px;
        font-weight: 500;
        transition: all 0.2s ease;
    }
    
    .application-actions button:hover {
        transform: translateY(-1px);
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .modal-container {
            width: 95%;
            max-height: 90vh;
            margin: 20px auto;
        }
        
        .modal-container.modal-large {
            width: 95%;
        }
        
        .modal-header {
            padding: 16px 20px;
        }
        
        .modal-body {
            padding: 20px;
        }
        
        .modal-footer {
            padding: 16px 20px;
        }
        
        .modal-form-grid {
            grid-template-columns: 1fr;
            gap: 16px;
        }
        
        .job-details-header {
            flex-direction: column;
            align-items: flex-start !important;
            gap: 16px;
        }
        
        .job-details-footer {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }
        
        .job-actions {
            display: flex;
            flex-direction: column;
            width: 100%;
            gap: 10px;
        }
        
        .job-actions button {
            width: 100%;
        }
        
        .application-header {
            flex-direction: column;
            gap: 12px;
        }
        
        .application-date {
            align-self: flex-start;
            margin-top: 8px;
        }
        
        .application-footer {
            flex-direction: column;
            align-items: flex-start;
            gap: 16px;
        }
        
        .application-actions {
            width: 100%;
        }
        
        .application-actions button {
            flex: 1;
            white-space: nowrap;
        }
    }
    
    @media (max-width: 480px) {
        .modal-container {
            width: 98%;
            margin: 10px auto;
        }
        
        .applicant-info {
            gap: 12px;
        }
        
        .applicant-avatar {
            width: 50px;
            height: 50px;
        }
        
        .application-card {
            padding: 16px;
        }
    }

    .modal-footer {
        padding: 16px 24px;
        border-top: 1px solid #f3f4f6;
        background-color: #f9fafb;
        border-radius: 0 0 16px 16px;
        position: sticky;
        bottom: 0;
        display: flex;
        justify-content: flex-end;
        align-items: center;
        gap: 12px;
    }

    /* Button styling */
    .modal-button {
        padding: 10px 18px;
        border-radius: 8px;
        font-size: 15px;
        font-weight: 500;
        transition: all 0.2s ease;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 110px;
    }

    .modal-button-primary {
        background-color: #2563eb;
        color: white;
        border: none;
    }

    .modal-button-primary:hover {
        background-color: #1d4ed8;
        transform: translateY(-1px);
    }

    .modal-button-secondary {
        background-color: #f9fafb;
        color: #374151;
        border: 1px solid #e5e7eb;
    }

    .modal-button-secondary:hover {
        background-color: #f3f4f6;
        transform: translateY(-1px);
    }

    .modal-button-danger {
        background-color: #ef4444;
        color: white;
        border: none;
    }

    .modal-button-danger:hover {
        background-color: #dc2626;
        transform: translateY(-1px);
    }
</style>

<!-- JavaScript to handle modal interactions -->
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Function to open edit job modal and load job data
        window.editJob = function(jobId) {
            // Set the job ID
            document.querySelector('#edit_job_id').value = jobId;
            
            // Here you would typically fetch the job data and populate the form
            // (This would be implemented in the client.php file's initializeJobInteractions function)
            
            // Close job details modal
            closeModal('jobDetailsModal');
            
            // Open edit modal
            setTimeout(() => {
                openModal('editJobModal');
            }, 300);
        };
        
        // Function to load applications for a job
        window.loadApplications = function(jobId) {
            // Update application modal content
            // (This would be implemented in the client.php file's loadApplications function)
            
            // Close job details modal if open
            closeModal('jobDetailsModal');
            
            // Open applications modal
            setTimeout(() => {
                openModal('viewApplicationsModal');
            }, 300);
        };
        
        // Helper functions for custom modal implementation
        function fetchJobDetails(jobId, mode) {
            // This would typically be an AJAX call to your backend
            // For demo purposes, we're simulating with a timeout
            
            // Set the job ID for the edit form
            if (mode === 'edit') {
                document.getElementById('edit_job_id').value = jobId;
                // In a real implementation, you would populate the form with job data from an AJAX call
            } else {
                // Set the job ID for the edit button in the details modal
                document.querySelector('.edit-job-btn').setAttribute('data-job-id', jobId);
                
                // In a real implementation, you would populate the details with job data from an AJAX call
                const detailsContainer = document.querySelector('.job-details-content');
                detailsContainer.innerHTML = '<div class="text-center py-5"><div class="spinner"><span class="visually-hidden">Loading...</span></div><p class="mt-2">Loading job details...</p></div>';
                
                // Simulate loading with timeout
                setTimeout(() => {
                    // This would be replaced with real data from your backend
                    detailsContainer.innerHTML = generateJobDetailCardLayout();
                }, 1000);
            }
        }
        
        // Function to generate Shadcn UI-inspired card layout for job details
        function generateJobDetailCardLayout() {
            return `
                <div class="job-details">
                    <div class="job-details-header">
                        <div>
                            <h3>WordPress E-commerce Website Development</h3>
                            <div class="badge-container">
                                <div class="status-wrapper">
                                    <span class="badge bg-success">Active</span>
                                </div>
                                <span class="badge">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                                    Web Development
                                </span>
                                <span class="badge">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><polyline points="12 6 12 12 16 14"></polyline></svg>
                                    3-4 weeks
                                </span>
                            </div>
                        </div>
                        <div class="status-wrapper">
                            <span class="badge bg-success">$1,500</span>
                        </div>
                    </div>
                    
                    <!-- Job meta information in grid -->
                    <div class="job-detail-card">
                        <h4 class="detail-section-title">Project Overview</h4>
                        <div class="job-meta-grid">
                            <div class="job-meta-item">
                                <span class="job-meta-label">Experience Level</span>
                                <span class="job-meta-value">Intermediate</span>
                            </div>
                            <div class="job-meta-item">
                                <span class="job-meta-label">Duration</span>
                                <span class="job-meta-value">3-4 weeks</span>
                            </div>
                            <div class="job-meta-item">
                                <span class="job-meta-label">Posted On</span>
                                <span class="job-meta-value">June 12, 2023</span>
                            </div>
                            <div class="job-meta-item">
                                <span class="job-meta-label">Applications</span>
                                <span class="job-meta-value">7 applicants</span>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Job description -->
                    <div class="job-detail-card">
                        <h4 class="detail-section-title">Job Description</h4>
                        <div class="job-description editable-field">
                            <div class="editable-content">
                                <p>I need an experienced WordPress developer to build an e-commerce website for my clothing brand. The website should be responsive, fast-loading, and integrate with popular payment gateways.</p>
                                
                                <p>Key requirements:</p>
                                <ul>
                                    <li>Custom theme development based on provided designs</li>
                                    <li>WooCommerce integration with custom product categories</li>
                                    <li>Payment gateway integration (Stripe, PayPal)</li>
                                    <li>Mobile-friendly responsive design</li>
                                    <li>SEO optimization</li>
                                </ul>
                                
                                <p>The ideal candidate will have extensive experience with WordPress, WooCommerce, and custom theme development.</p>
                            </div>
                            <button class="edit-field-btn" data-field="description">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                Edit
                            </button>
                        </div>
                    </div>
                    
                    <!-- Skills section -->
                    <div class="job-detail-card">
                        <h4 class="detail-section-title">Required Skills</h4>
                        <div class="editable-field">
                            <div class="skills-list">
                                <span class="badge">WordPress</span>
                                <span class="badge">WooCommerce</span>
                                <span class="badge">PHP</span>
                                <span class="badge">CSS</span>
                                <span class="badge">JavaScript</span>
                                <span class="badge">Responsive Design</span>
                                <span class="badge">E-commerce</span>
                            </div>
                            <button class="edit-field-btn" data-field="skills">
                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                Edit
                            </button>
                        </div>
                    </div>
                    
                    <!-- Footer with actions -->
                    <div class="job-details-footer">
                        <div class="job-meta">
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                                Posted on June 12, 2023
                            </div>
                            <div>
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                                7 Applicants
                            </div>
                        </div>
                        <div class="job-actions">
                            <button class="action-button" onclick="loadApplications('123')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>
                                View Applicants
                            </button>
                            <button class="action-button action-button-primary" onclick="editJob('123')">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7"></path><path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z"></path></svg>
                                Edit Job
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }
    });
</script>

<!-- Modal Functionality -->
<script>
    // Extend modal functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Ensure the global modal functions from modal.js are used, we're just extending them
        const originalOpenModal = window.openModal || function(){};
        
        // Override global openModal function to add our custom behavior
        window.openModal = function(modalId) {
            console.log("Opening modal:", modalId);
            const modal = document.getElementById(modalId);
            
            // Call the original function first
            originalOpenModal(modalId);
            
            if (modal) {
                // Focus on first input if it exists
                const firstInput = modal.querySelector('input, select, textarea');
                if (firstInput) {
                    setTimeout(() => {
                        firstInput.focus();
                    }, 100);
                }
                
                // Add ESC key handler
                document.addEventListener('keydown', function escHandler(e) {
                    if (e.key === 'Escape') {
                        closeModal(modalId);
                        document.removeEventListener('keydown', escHandler);
                    }
                });
            
            // Log the modal opening for debugging            console.log(`Modal ${modalId} opened`);
            } else {
                console.error(`Modal with ID ${modalId} not found`);
            }
        });
    }
      // Extend the closeModal function if it exists
    const originalCloseModal = window.closeModal || function(){};
    
    window.closeModal = function(modalId) {
        console.log("Closing modal:", modalId);
        // Call original function
        originalCloseModal(modalId);
        
        const modal = document.getElementById(modalId);
        if (modal) {
            // Make sure body scroll is restored
            document.body.style.overflow = '';
            
            // Log the modal closing for debugging
            console.log(`Modal ${modalId} closed`);
        } else {
            console.error(`Modal with ID ${modalId} not found`);
        }
    }
    
    // Close modal when clicking on overlay
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('modal-overlay') && !e.target.classList.contains('no-close-on-overlay')) {
            const modalId = e.target.id;
            closeModal(modalId);
        }
    });
    
    // Initialize all modals
    document.addEventListener('DOMContentLoaded', function() {
        console.log("Initializing modals");
        
        // Add click event for all elements with data-open-modal attribute
        document.querySelectorAll('[data-open-modal]').forEach(button => {
            button.addEventListener('click', function() {
                const modalId = this.dataset.openModal;
                openModal(modalId);
            });
        });
        
        // Add click event for all elements with data-close-modal attribute
        document.querySelectorAll('[data-close-modal]').forEach(button => {
            button.addEventListener('click', function() {
                const modalId = this.dataset.closeModal;
                closeModal(modalId);
            });
        });
    });
</script>

