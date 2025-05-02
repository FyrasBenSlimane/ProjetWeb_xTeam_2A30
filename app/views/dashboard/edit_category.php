<?php

/**
 * Admin Dashboard - Edit Category View
 */

// Redirect if not admin
if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_account_type']) || $_SESSION['user_account_type'] !== 'admin') {
    redirect('users/login');
}

// Ensure we have category data
if (!isset($data['category']) || empty($data['category'])) {
    redirect('dashboard/community?section=forums');
}

$category = $data['category'];
?>

<!-- Include community stylesheet -->
<link rel="stylesheet" href="<?php echo URL_ROOT; ?>/public/css/community.css">

<!-- Edit Category Content -->
<div class="dashboard-content">
    <!-- Page Header -->
    <div class="head-title">
        <div class="left">
            <h1>Edit Forum Category</h1>
            <ul class="breadcrumb">
                <li><a href="<?php echo URL_ROOT; ?>/dashboard">Dashboard</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a href="<?php echo URL_ROOT; ?>/dashboard/community?section=forums">Community</a></li>
                <li><i class='bx bx-chevron-right'></i></li>
                <li><a class="active" href="#">Edit Category</a></li>
            </ul>
        </div>
        <div class="btn-download">
            <a href="<?php echo URL_ROOT; ?>/dashboard/community?section=forums" class="btn">
                <i class='bx bx-arrow-back'></i>
                <span class="text">Back to Community</span>
            </a>
        </div>
    </div>

    <!-- Display alert messages -->
    <?php flash('dashboard_message'); ?>

    <!-- Edit Category Form Card -->
    <div class="card">
        <div class="card-header">
            <div class="d-flex align-items-center">
                <?php if (!empty($category->icon)): ?>
                    <i class='bx <?php echo htmlspecialchars($category->icon); ?> mr-2' style="color: <?php echo htmlspecialchars($category->color ?? '#3C91E6'); ?>; font-size: 24px;"></i>
                <?php endif; ?>
                <h3>Edit Category: <?php echo htmlspecialchars($category->name); ?></h3>
            </div>
        </div>
        <div class="card-body">
            <form action="<?php echo URL_ROOT; ?>/dashboard/updateCategory/<?php echo $category->id; ?>" method="POST" id="editCategoryForm">
                <div class="form-group">
                    <label for="name">Category Name <span class="text-danger">*</span></label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        value="<?php echo htmlspecialchars($category->name); ?>" 
                        class="<?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>"
                        required
                        autocomplete="off"
                        maxlength="50"
                    >
                    <div class="help-text">
                        <span class="char-count" id="nameCharCount"><?php echo strlen($category->name); ?>/50</span>
                    </div>
                    <?php if (!empty($data['name_err'])) : ?>
                        <div class="invalid-feedback"><?php echo $data['name_err']; ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea 
                        name="description" 
                        id="description" 
                        rows="3"
                        class="<?php echo (!empty($data['description_err'])) ? 'is-invalid' : ''; ?>"
                        maxlength="255"
                    ><?php echo htmlspecialchars($category->description ?? ''); ?></textarea>
                    <div class="help-text">
                        Brief description of what this category is about.
                        <span class="char-count" id="descCharCount"><?php echo strlen($category->description ?? ''); ?>/255</span>
                    </div>
                    <?php if (!empty($data['description_err'])) : ?>
                        <div class="invalid-feedback"><?php echo $data['description_err']; ?></div>
                    <?php endif; ?>
                </div>

                <div class="form-row">
                    <div class="form-group col-md-6">
                        <label for="icon">Icon</label>
                        <div class="d-flex align-items-center mb-2">
                            <div id="iconPreview" class="icon-preview mr-2">
                                <?php if (!empty($category->icon)): ?>
                                    <i class='bx <?php echo htmlspecialchars($category->icon); ?>'></i>
                                <?php endif; ?>
                            </div>
                            <input 
                                type="text" 
                                name="icon" 
                                id="icon" 
                                value="<?php echo htmlspecialchars($category->icon ?? ''); ?>" 
                                placeholder="e.g. bx-chat"
                                autocomplete="off"
                            >
                        </div>
                        <div class="help-text">Choose from the icons below or enter a Boxicons class name.</div>

                        <!-- Icon Picker -->
                        <div id="iconPickerContainer" class="icon-picker-container mt-2">
                            <div class="icon-search mb-2">
                                <input 
                                    type="text" 
                                    id="iconSearch" 
                                    placeholder="Search icons..." 
                                    autocomplete="off"
                                >
                            </div>
                            <div class="icon-grid">
                                <?php
                                $popularIcons = [
                                    'bx-chat', 'bxs-chat', 'bx-message', 'bxs-message', 'bx-conversation',
                                    'bxs-conversation', 'bx-category', 'bxs-category', 'bx-help-circle', 
                                    'bxs-help-circle', 'bx-code-block', 'bxs-file-code', 'bx-code',
                                    'bxs-chip', 'bx-bulb', 'bxs-bulb', 'bx-group', 'bxs-group',
                                    'bx-briefcase', 'bxs-briefcase', 'bx-book', 'bxs-book',
                                    'bx-user', 'bxs-user', 'bx-star', 'bxs-star',
                                    'bx-bell', 'bxs-bell', 'bx-calendar', 'bxs-calendar',
                                    'bx-folder', 'bxs-folder', 'bx-home', 'bxs-home',
                                    'bx-cog', 'bxs-cog', 'bx-shield', 'bxs-shield'
                                ];

                                foreach ($popularIcons as $icon):
                                    $isSelected = ($category->icon === $icon) ? 'selected' : '';
                                ?>
                                    <div class="icon-item <?php echo $isSelected; ?>" data-icon="<?php echo $icon; ?>" title="<?php echo $icon; ?>">
                                        <i class='bx <?php echo $icon; ?>'></i>
                                    </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        <label for="color">Color</label>
                        <div class="color-picker-container">
                            <input 
                                type="color" 
                                name="color" 
                                id="color" 
                                value="<?php echo htmlspecialchars($category->color ?? '#3C91E6'); ?>" 
                                class="color-picker"
                            >
                            <input 
                                type="text" 
                                id="colorHex" 
                                value="<?php echo htmlspecialchars($category->color ?? '#3C91E6'); ?>" 
                                class="color-hex" 
                                placeholder="#RRGGBB"
                            >
                        </div>
                        <div class="help-text">Choose a color for this category.</div>
                        
                        <!-- Color Presets -->
                        <div class="color-presets mt-2">
                            <div class="color-preset" data-color="#3C91E6" style="background-color: #3C91E6;"></div>
                            <div class="color-preset" data-color="#38CADD" style="background-color: #38CADD;"></div>
                            <div class="color-preset" data-color="#5D6CD6" style="background-color: #5D6CD6;"></div>
                            <div class="color-preset" data-color="#A66DD4" style="background-color: #A66DD4;"></div>
                            <div class="color-preset" data-color="#FD7238" style="background-color: #FD7238;"></div>
                            <div class="color-preset" data-color="#42B883" style="background-color: #42B883;"></div>
                            <div class="color-preset" data-color="#6C757D" style="background-color: #6C757D;"></div>
                            <div class="color-preset" data-color="#F03A47" style="background-color: #F03A47;"></div>
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="display_order">Display Order</label>
                    <input 
                        type="number" 
                        name="display_order" 
                        id="display_order" 
                        value="<?php echo $category->display_order ?? 0; ?>" 
                        min="0"
                    >
                    <div class="help-text">Lower numbers appear first in the category list.</div>
                </div>

                <div class="form-group">
                    <label>Permissions</label>
                    <div class="permissions-container mt-2">
                        <div class="permission-card">
                            <div class="permission-header">
                                <div class="form-check">
                                    <input 
                                        type="checkbox" 
                                        name="perm_all_view" 
                                        id="perm_all_view" 
                                        <?php echo ($category->perm_all_view ?? true) ? 'checked' : ''; ?>
                                    >
                                    <label class="form-check-label" for="perm_all_view">
                                        <i class='bx bx-show'></i> View Topics
                                    </label>
                                </div>
                            </div>
                            <div class="permission-body">
                                Anyone can view topics in this category
                            </div>
                        </div>
                        
                        <div class="permission-card">
                            <div class="permission-header">
                                <div class="form-check">
                                    <input 
                                        type="checkbox" 
                                        name="perm_all_create" 
                                        id="perm_all_create" 
                                        <?php echo ($category->perm_all_create ?? true) ? 'checked' : ''; ?>
                                    >
                                    <label class="form-check-label" for="perm_all_create">
                                        <i class='bx bx-plus-circle'></i> Create Topics
                                    </label>
                                </div>
                            </div>
                            <div class="permission-body">
                                Anyone can create new topics in this category
                            </div>
                        </div>
                        
                        <div class="permission-card">
                            <div class="permission-header">
                                <div class="form-check">
                                    <input 
                                        type="checkbox" 
                                        name="perm_all_reply" 
                                        id="perm_all_reply" 
                                        <?php echo ($category->perm_all_reply ?? true) ? 'checked' : ''; ?>
                                    >
                                    <label class="form-check-label" for="perm_all_reply">
                                        <i class='bx bx-reply'></i> Reply to Topics
                                    </label>
                                </div>
                            </div>
                            <div class="permission-body">
                                Anyone can reply to topics in this category
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn-sm save">
                        <i class='bx bx-save'></i> Save Changes
                    </button>
                    <a href="<?php echo URL_ROOT; ?>/dashboard/community?section=forums" class="btn-sm cancel">
                        <i class='bx bx-x'></i> Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Category Preview Card -->
    <div class="card mt-4">
        <div class="card-header">
            <h3>Category Preview</h3>
        </div>
        <div class="card-body">
            <div class="category-preview">
                <div class="category-item" id="categoryPreview">
                    <div class="category-icon" id="previewIcon" style="background-color: <?php echo htmlspecialchars($category->color ?? '#3C91E6'); ?>;">
                        <i class='bx <?php echo htmlspecialchars($category->icon ?? 'bx-category'); ?>'></i>
                    </div>
                    <div class="category-details">
                        <h4 id="previewName"><?php echo htmlspecialchars($category->name); ?></h4>
                        <p id="previewDescription"><?php echo htmlspecialchars($category->description ?? 'No description provided.'); ?></p>
                    </div>
                    <div class="category-meta">
                        <div class="meta-item">
                            <i class='bx bx-message'></i> <span>0 Topics</span>
                        </div>
                        <div class="meta-item">
                            <i class='bx bx-reply'></i> <span>0 Replies</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Setup form validation
        const editForm = document.getElementById('editCategoryForm');
        const nameInput = document.getElementById('name');
        const descInput = document.getElementById('description');
        const nameCharCount = document.getElementById('nameCharCount');
        const descCharCount = document.getElementById('descCharCount');
        
        // Character counters
        nameInput.addEventListener('input', function() {
            const count = this.value.length;
            nameCharCount.textContent = count + '/50';
            nameCharCount.classList.toggle('text-danger', count > 50);
            updatePreview();
        });
        
        descInput.addEventListener('input', function() {
            const count = this.value.length;
            descCharCount.textContent = count + '/255';
            descCharCount.classList.toggle('text-danger', count > 255);
            updatePreview();
        });
        
        // Setup icon picker functionality
        const iconInput = document.getElementById('icon');
        const iconPreview = document.getElementById('iconPreview');
        const iconItems = document.querySelectorAll('.icon-item');
        const iconSearch = document.getElementById('iconSearch');

        // Handle icon selection
        iconItems.forEach(item => {
            item.addEventListener('click', function() {
                const iconName = this.dataset.icon;
                iconInput.value = iconName;
                updateIconPreview(iconName);

                // Highlight selected icon
                iconItems.forEach(i => i.classList.remove('selected'));
                this.classList.add('selected');
                
                updatePreview();
            });
        });

        // Icon search functionality
        iconSearch.addEventListener('input', function() {
            const searchTerm = this.value.toLowerCase();
            iconItems.forEach(item => {
                const iconName = item.dataset.icon.toLowerCase();
                if (iconName.includes(searchTerm)) {
                    item.style.display = 'flex';
                } else {
                    item.style.display = 'none';
                }
            });
        });

        // Update on input change
        iconInput.addEventListener('input', function() {
            updateIconPreview(this.value);
            updatePreview();
        });

        // Initial preview
        updateIconPreview(iconInput.value);

        function updateIconPreview(iconName) {
            if (iconName && iconName.trim() !== '') {
                iconPreview.innerHTML = `<i class="bx ${iconName}"></i>`;
                iconPreview.classList.remove('empty');
            } else {
                iconPreview.innerHTML = '<i class="bx bx-category"></i>';
                iconPreview.classList.add('empty');
            }
        }

        // Setup color picker
        const colorInput = document.getElementById('color');
        const colorHex = document.getElementById('colorHex');
        const colorPresets = document.querySelectorAll('.color-preset');
        
        // Sync color inputs
        colorInput.addEventListener('input', function() {
            colorHex.value = this.value;
            updatePreview();
        });
        
        colorHex.addEventListener('input', function() {
            // Add # if missing
            if (!this.value.startsWith('#') && this.value.length > 0) {
                this.value = '#' + this.value;
            }
            
            // Validate hex color
            if (/^#([0-9A-F]{3}){1,2}$/i.test(this.value)) {
                colorInput.value = this.value;
                updatePreview();
            }
        });
        
        // Color presets
        colorPresets.forEach(preset => {
            preset.addEventListener('click', function() {
                const presetColor = this.dataset.color;
                colorInput.value = presetColor;
                colorHex.value = presetColor;
                updatePreview();
            });
        });
        
        // Live preview update
        function updatePreview() {
            const previewName = document.getElementById('previewName');
            const previewDescription = document.getElementById('previewDescription');
            const previewIcon = document.getElementById('previewIcon');
            
            previewName.textContent = nameInput.value || 'Category Name';
            previewDescription.textContent = descInput.value || 'No description provided.';
            
            const iconClass = iconInput.value || 'bx-category';
            previewIcon.querySelector('i').className = `bx ${iconClass}`;
            previewIcon.style.backgroundColor = colorInput.value;
        }
        
        // Initial preview update
        updatePreview();
        
        // Form submission validation
        editForm.addEventListener('submit', function(e) {
            let isValid = true;
            
            // Validate name (required)
            if (nameInput.value.trim() === '') {
                nameInput.classList.add('is-invalid');
                isValid = false;
            } else if (nameInput.value.length > 50) {
                nameInput.classList.add('is-invalid');
                isValid = false;
            } else {
                nameInput.classList.remove('is-invalid');
            }
            
            // Validate description (optional)
            if (descInput.value.length > 255) {
                descInput.classList.add('is-invalid');
                isValid = false;
            } else {
                descInput.classList.remove('is-invalid');
            }
            
            if (!isValid) {
                e.preventDefault();
            }
        });
    });
</script>

<style>
    /* Enhanced Form Styling */
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-row {
        display: flex;
        flex-wrap: wrap;
        margin-right: -10px;
        margin-left: -10px;
    }
    
    .form-row > .form-group {
        padding-right: 10px;
        padding-left: 10px;
        margin-bottom: 0;
    }
    
    .col-md-6 {
        flex: 0 0 50%;
        max-width: 50%;
    }
    
    @media (max-width: 768px) {
        .col-md-6 {
            flex: 0 0 100%;
            max-width: 100%;
        }
        
        .form-row > .form-group:first-child {
            margin-bottom: 1.5rem;
        }
    }
    
    label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 600;
    }
    
    input[type="text"],
    input[type="number"],
    textarea {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        transition: border-color 0.2s;
    }
    
    input[type="text"]:focus,
    input[type="number"]:focus,
    textarea:focus {
        border-color: #3C91E6;
        outline: none;
        box-shadow: 0 0 0 2px rgba(60, 145, 230, 0.25);
    }
    
    .is-invalid {
        border-color: #dc3545 !important;
    }
    
    .invalid-feedback {
        color: #dc3545;
        font-size: 0.85rem;
        margin-top: 0.25rem;
    }
    
    .help-text {
        color: #6c757d;
        font-size: 0.85rem;
        margin-top: 0.25rem;
        display: flex;
        justify-content: space-between;
    }
    
    .char-count {
        font-family: monospace;
    }
    
    .text-danger {
        color: #dc3545 !important;
    }
    
    /* Icon Picker */
    .icon-preview {
        display: flex;
        justify-content: center;
        align-items: center;
        width: 40px;
        height: 40px;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-right: 10px;
        background-color: #f8f9fa;
    }
    
    .icon-preview i {
        font-size: 24px;
    }
    
    .icon-picker-container {
        border: 1px solid #ddd;
        border-radius: 4px;
        padding: 10px;
        background-color: #f8f9fa;
    }
    
    .icon-search {
        margin-bottom: 10px;
    }
    
    .icon-search input {
        width: 100%;
        padding: 5px 10px;
    }
    
    .icon-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(40px, 1fr));
        gap: 8px;
        max-height: 200px;
        overflow-y: auto;
    }
    
    .icon-item {
        display: flex;
        align-items: center;
        justify-content: center;
        height: 40px;
        border: 1px solid #ddd;
        border-radius: 4px;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .icon-item:hover {
        background-color: #e9ecef;
    }
    
    .icon-item.selected {
        border-color: #3C91E6;
        background-color: rgba(60, 145, 230, 0.1);
    }
    
    .icon-item i {
        font-size: 20px;
    }
    
    /* Color Picker */
    .color-picker-container {
        display: flex;
        align-items: center;
    }
    
    .color-picker {
        width: 40px;
        height: 40px;
        padding: 0;
        border: none;
        border-radius: 4px;
        margin-right: 10px;
        cursor: pointer;
    }
    
    .color-hex {
        width: calc(100% - 50px);
    }
    
    .color-presets {
        display: flex;
        flex-wrap: wrap;
        gap: 8px;
    }
    
    .color-preset {
        width: 30px;
        height: 30px;
        border-radius: 4px;
        cursor: pointer;
        transition: transform 0.1s;
    }
    
    .color-preset:hover {
        transform: scale(1.1);
    }
    
    /* Permissions */
    .permissions-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 15px;
    }
    
    .permission-card {
        border: 1px solid #ddd;
        border-radius: 4px;
    }
    
    .permission-header {
        padding: 10px;
        background-color: #f8f9fa;
        border-bottom: 1px solid #ddd;
        font-weight: 600;
    }
    
    .permission-header label {
        margin-bottom: 0;
        display: flex;
        align-items: center;
    }
    
    .permission-header i {
        margin-right: 8px;
    }
    
    .permission-body {
        padding: 10px;
        color: #6c757d;
        font-size: 0.9rem;
    }
    
    /* Form Actions */
    .form-actions {
        display: flex;
        justify-content: flex-start;
        gap: 10px;
        margin-top: 20px;
        padding-top: 20px;
        border-top: 1px solid #ddd;
    }
    
    .btn-sm {
        display: inline-flex;
        align-items: center;
        padding: 8px 16px;
        border-radius: 4px;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .btn-sm i {
        margin-right: 8px;
    }
    
    .btn-sm.save {
        background-color: #3C91E6;
        color: white;
    }
    
    .btn-sm.save:hover {
        background-color: #2e7bc7;
    }
    
    .btn-sm.cancel {
        background-color: #6c757d;
        color: white;
        text-decoration: none;
    }
    
    .btn-sm.cancel:hover {
        background-color: #5a6268;
    }

    /* Category Preview */
    .category-preview {
        margin: 0 -15px;
    }
    
    .category-item {
        display: flex;
        padding: 15px;
        border-radius: 8px;
        background-color: #fff;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        margin-bottom: 15px;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    
    .category-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        margin-right: 15px;
        flex-shrink: 0;
    }
    
    .category-icon i {
        font-size: 24px;
        color: white;
    }
    
    .category-details {
        flex: 1;
    }
    
    .category-details h4 {
        margin: 0 0 5px 0;
        font-size: 1.1rem;
    }
    
    .category-details p {
        margin: 0;
        color: #6c757d;
        font-size: 0.9rem;
    }
    
    .category-meta {
        display: flex;
        align-items: center;
        gap: 15px;
    }
    
    .meta-item {
        display: flex;
        align-items: center;
        color: #6c757d;
        font-size: 0.85rem;
    }
    
    .meta-item i {
        margin-right: 5px;
    }

    /* Responsive Fixes */
    @media (max-width: 768px) {
        .permissions-container {
            grid-template-columns: 1fr;
        }
        
        .category-meta {
            flex-direction: column;
            align-items: flex-start;
            gap: 5px;
        }
    }

    /* Accessibility */
    input[type="checkbox"] {
        min-width: 18px;
        min-height: 18px;
    }
</style>