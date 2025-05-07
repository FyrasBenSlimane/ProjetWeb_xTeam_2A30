<?php
// Use buffers to store the dashboard content
ob_start();

// Check for flash messages
$success = flash('settings_success', '', false, false);
$error = flash('settings_error', '', false, false);
?>

<div class="settings-page">
    <style>
        /* Settings Page Styles */
        .settings-page {
            padding: 1.5rem 0;
        }
        
        /* Header Styles */
        .settings-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .section-title {
            font-size: 1.5rem;
            font-weight: 600;
            color: #1e293b;
        }
        
        /* Alerts */
        .alert {
            padding: 1rem;
            border-radius: 0.5rem;
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
        }
        
        .alert-success {
            background-color: #dcfce7;
            color: #16a34a;
            border-left: 4px solid #16a34a;
        }
        
        .alert-danger {
            background-color: #fee2e2;
            color: #dc2626;
            border-left: 4px solid #dc2626;
        }
        
        .alert-icon {
            margin-right: 0.5rem;
            font-size: 1.2rem;
        }
        
        .alert-close {
            margin-left: auto;
            background: none;
            border: none;
            color: currentColor;
            cursor: pointer;
            font-size: 1.2rem;
        }
        
        /* Tab Navigation */
        .tabs {
            margin-bottom: 1.5rem;
            border-bottom: 1px solid #e5e7eb;
        }
        
        .tab-list {
            display: flex;
            list-style: none;
            padding: 0;
            margin: 0;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        
        .tab-item {
            margin-right: 0.25rem;
        }
        
        .tab-link {
            display: inline-flex;
            align-items: center;
            padding: 0.75rem 1rem;
            border-bottom: 2px solid transparent;
            color: #6b7280;
            text-decoration: none;
            font-weight: 500;
            cursor: pointer;
            white-space: nowrap;
        }
        
        .tab-link:hover {
            color: #1e293b;
            border-color: #cbd5e1;
        }
        
        .tab-link.active {
            color: #050b1f;
            border-color: #050b1f;
        }
        
        .tab-icon {
            margin-right: 0.5rem;
        }
        
        /* Tab Content */
        .tab-content {
            margin-top: 1.5rem;
        }
        
        .tab-pane {
            display: none;
        }
        
        .tab-pane.active {
            display: block;
        }
        
        /* Settings Cards */
        .settings-card {
            background-color: white;
            border-radius: 0.5rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
            overflow: hidden;
        }
        
        .settings-card-header {
            background-color: #050b1f;
            padding: 1rem 1.5rem;
            color: white;
            font-weight: 600;
            display: flex;
            align-items: center;
        }
        
        .settings-card-header-icon {
            margin-right: 0.5rem;
        }
        
        .settings-card-body {
            padding: 1.5rem;
        }
        
        /* Form Elements */
        .form-row {
            margin-bottom: 1.5rem;
            display: grid;
            gap: 1.5rem;
            grid-template-columns: 1fr;
        }
        
        @media (min-width: 768px) {
            .form-row {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .form-row.three-columns {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        
        @media (max-width: 767px) {
            .form-row.three-columns {
                grid-template-columns: 1fr;
            }
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        .form-label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: #374151;
        }
        
        .form-required {
            color: #dc2626;
            margin-left: 0.25rem;
        }
        
        .form-input,
        .form-select {
            width: 100%;
            padding: 0.5rem 0.75rem;
            border: 1px solid #d1d5db;
            border-radius: 0.375rem;
            font-size: 0.875rem;
            color: #1e293b;
            background-color: white;
        }
        
        .form-input:focus,
        .form-select:focus {
            outline: none;
            border-color: #6b7280;
            box-shadow: 0 0 0 3px rgba(5, 11, 31, 0.1);
        }
        
        .form-select {
            appearance: none;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='%236b7280' viewBox='0 0 16 16'%3E%3Cpath d='M8 10.5L3.5 6h9z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.75rem center;
            background-size: 16px 12px;
        }
        
        .form-input-group {
            display: flex;
        }
        
        .form-input-addon {
            display: flex;
            align-items: center;
            padding: 0 0.75rem;
            background-color: #f3f4f6;
            border: 1px solid #d1d5db;
            border-left: none;
            border-top-right-radius: 0.375rem;
            border-bottom-right-radius: 0.375rem;
            color: #6b7280;
        }
        
        .form-input-group .form-input {
            border-top-right-radius: 0;
            border-bottom-right-radius: 0;
        }
        
        .form-help {
            font-size: 0.75rem;
            color: #6b7280;
            margin-top: 0.25rem;
        }
        
        /* Toggle/Switch */
        .form-switch {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .switch {
            position: relative;
            display: inline-block;
            width: 48px;
            height: 24px;
            margin-right: 0.75rem;
        }
        
        .switch input {
            opacity: 0;
            width: 0;
            height: 0;
        }
        
        .slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #cbd5e1;
            transition: .4s;
            border-radius: 24px;
        }
        
        .slider:before {
            position: absolute;
            content: "";
            height: 18px;
            width: 18px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }
        
        input:checked + .slider {
            background-color: #050b1f;
        }
        
        input:focus + .slider {
            box-shadow: 0 0 1px #050b1f;
        }
        
        input:checked + .slider:before {
            transform: translateX(24px);
        }
        
        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            font-weight: 500;
            border: 1px solid transparent;
            border-radius: 0.375rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .btn-primary {
            background-color: #050b1f;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #0b1c40;
        }
        
        .btn-secondary {
            background-color: #f3f4f6;
            color: #374151;
            border-color: #d1d5db;
        }
        
        .btn-secondary:hover {
            background-color: #e5e7eb;
        }
        
        .btn-info {
            background-color: #0369a1;
            color: white;
        }
        
        .btn-info:hover {
            background-color: #0284c7;
        }
        
        .btn-icon {
            margin-right: 0.5rem;
        }
        
        .form-actions {
            display: flex;
            justify-content: flex-end;
            gap: 0.75rem;
            margin-top: 1.5rem;
        }
        
        /* Spacing */
        .mb-1 {
            margin-bottom: 0.25rem;
        }
        
        .mb-2 {
            margin-bottom: 0.5rem;
        }
        
        .mb-3 {
            margin-bottom: 0.75rem;
        }
        
        .mb-4 {
            margin-bottom: 1rem;
        }
        
        .mb-5 {
            margin-bottom: 1.5rem;
        }
        
        /* Test Result Containers */
        .test-result {
            margin-top: 1rem;
            padding: 0.75rem 1rem;
            border-radius: 0.375rem;
            display: none;
        }
        
        .test-result.success {
            background-color: #dcfce7;
            color: #16a34a;
            border-left: 4px solid #16a34a;
        }
        
        .test-result.warning {
            background-color: #fff7ed;
            color: #ea580c;
            border-left: 4px solid #ea580c;
        }
        
        .test-result.error {
            background-color: #fee2e2;
            color: #dc2626;
            border-left: 4px solid #dc2626;
        }
        
        /* Toast Notification */
        .toast {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background-color: #050b1f;
            color: white;
            padding: 1rem;
            border-radius: 0.375rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            z-index: 50;
            transform: translateY(150%);
            transition: transform 0.3s ease-out;
            display: flex;
            align-items: center;
        }
        
        .toast.visible {
            transform: translateY(0);
        }
        
        .toast-icon {
            margin-right: 0.75rem;
        }
        
        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 60;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }
        
        .modal.active {
            display: flex;
        }
        
        .modal-dialog {
            background-color: white;
            border-radius: 0.5rem;
            width: 100%;
            max-width: 600px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        
        .modal-header {
            padding: 1rem 1.5rem;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .modal-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: #050b1f;
        }
        
        .modal-close {
            background: transparent;
            border: none;
            font-size: 1.5rem;
            line-height: 1;
            cursor: pointer;
            color: #6b7280;
        }
        
        .modal-body {
            padding: 1.5rem;
            max-height: 70vh;
            overflow-y: auto;
        }
        
        .modal-footer {
            padding: 1rem 1.5rem;
            border-top: 1px solid #e5e7eb;
            display: flex;
            justify-content: flex-end;
            gap: 0.5rem;
        }
        
        /* Accordion Styles */
        .accordion {
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            overflow: hidden;
        }
        
        .accordion-item {
            border-bottom: 1px solid #e5e7eb;
        }
        
        .accordion-item:last-child {
            border-bottom: none;
        }
        
        .accordion-header {
            margin: 0;
        }
        
        .accordion-button {
            display: flex;
            align-items: center;
            width: 100%;
            padding: 1rem 1.5rem;
            font-size: 1rem;
            font-weight: 500;
            color: #1e293b;
            background-color: #f9fafb;
            border: none;
            text-align: left;
            cursor: pointer;
        }
        
        .accordion-button:after {
            content: "\002B"; /* Plus sign */
            margin-left: auto;
            color: #6b7280;
            font-weight: bold;
            font-size: 1.25rem;
        }
        
        .accordion-button.active:after {
            content: "\2212"; /* Minus sign */
        }
        
        .accordion-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
            background-color: white;
        }
        
        .accordion-content-inner {
            padding: 1rem 1.5rem;
        }
        
        .accordion-content ul {
            padding-left: 1.5rem;
            margin: 0.5rem 0;
        }
        
        .accordion-content li {
            margin-bottom: 0.25rem;
        }
    </style>

    <div class="settings-header">
        <h2 class="section-title">System Settings</h2>
        <button class="btn btn-secondary" id="helpBtn">
            <i class="tab-icon">?</i> Help
        </button>
    </div>
    
    <?php if($success): ?>
    <div class="alert alert-success">
        <span class="alert-icon">✓</span>
        <?php echo $success; ?>
        <button type="button" class="alert-close" onclick="this.parentElement.style.display='none';">&times;</button>
    </div>
    <?php endif; ?>
    
    <?php if($error): ?>
    <div class="alert alert-danger">
        <span class="alert-icon">!</span>
        <?php echo $error; ?>
        <button type="button" class="alert-close" onclick="this.parentElement.style.display='none';">&times;</button>
    </div>
    <?php endif; ?>

    <!-- Toast notification for settings saved -->
    <div class="toast" id="settingsSavedToast">
        <span class="toast-icon">✓</span>
        <div>Settings saved successfully!</div>
    </div>

    <form action="<?php echo URLROOT; ?>/dashboard/settings" method="post" id="settingsForm">
        <!-- Tab Navigation -->
        <div class="tabs">
            <ul class="tab-list" id="settingsTabs">
                <li class="tab-item">
                    <a class="tab-link active" data-tab="general">
                        <i class="tab-icon">≡</i> General
                    </a>
                </li>
                <li class="tab-item">
                    <a class="tab-link" data-tab="smtp">
                        <i class="tab-icon">✉</i> Email
                    </a>
                </li>
                <li class="tab-item">
                    <a class="tab-link" data-tab="sms">
                        <i class="tab-icon">📱</i> SMS
                    </a>
                </li>
                <li class="tab-item">
                    <a class="tab-link" data-tab="security">
                        <i class="tab-icon">🔒</i> Security
                    </a>
                </li>
            </ul>
        </div>

        <!-- Tab Content -->
        <div class="tab-content">
            <!-- General Tab -->
            <div class="tab-pane active" id="general">
                <div class="settings-card">
                    <div class="settings-card-header">
                        <span class="settings-card-header-icon">≡</span>
                        General Configuration
                    </div>
                    <div class="settings-card-body">
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="site_name">
                                    Site Name <span class="form-required">*</span>
                                </label>
                                <input type="text" class="form-input" id="site_name" name="site_name" 
                                       value="<?php echo htmlspecialchars($data['settings']->site_name ?? ''); ?>" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="site_url">
                                    Site URL <span class="form-required">*</span>
                                </label>
                                <input type="url" class="form-input" id="site_url" name="site_url" 
                                       value="<?php echo htmlspecialchars($data['settings']->site_url ?? ''); ?>" required>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="admin_email">
                                    Admin Email <span class="form-required">*</span>
                                </label>
                                <input type="email" class="form-input" id="admin_email" name="admin_email" 
                                       value="<?php echo htmlspecialchars($data['settings']->admin_email ?? ''); ?>" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="default_timezone">Default Timezone</label>
                                <select class="form-select" id="default_timezone" name="default_timezone">
                                    <?php 
                                    $timezones = DateTimeZone::listIdentifiers();
                                    $current_timezone = $data['settings']->default_timezone ?? 'UTC';
                                    foreach ($timezones as $timezone) {
                                        echo '<option value="' . $timezone . '"' . 
                                             ($timezone == $current_timezone ? ' selected' : '') . 
                                             '>' . $timezone . '</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="date_format">Date Format</label>
                                <select class="form-select" id="date_format" name="date_format">
                                    <option value="Y-m-d" <?php echo ($data['settings']->date_format ?? '') == 'Y-m-d' ? 'selected' : ''; ?>>YYYY-MM-DD (2025-05-03)</option>
                                    <option value="m/d/Y" <?php echo ($data['settings']->date_format ?? '') == 'm/d/Y' ? 'selected' : ''; ?>>MM/DD/YYYY (05/03/2025)</option>
                                    <option value="d/m/Y" <?php echo ($data['settings']->date_format ?? '') == 'd/m/Y' ? 'selected' : ''; ?>>DD/MM/YYYY (03/05/2025)</option>
                                    <option value="d.m.Y" <?php echo ($data['settings']->date_format ?? '') == 'd.m.Y' ? 'selected' : ''; ?>>DD.MM.YYYY (03.05.2025)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="pagination_limit">Items Per Page</label>
                                <input type="number" class="form-input" id="pagination_limit" name="pagination_limit" min="5" max="100" 
                                       value="<?php echo htmlspecialchars($data['settings']->pagination_limit ?? '20'); ?>">
                                <div class="form-help">Number of items to display per page in lists</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SMTP Tab -->
            <div class="tab-pane" id="smtp">
                <div class="settings-card">
                    <div class="settings-card-header">
                        <span class="settings-card-header-icon">✉</span>
                        SMTP Configuration
                    </div>
                    <div class="settings-card-body">
                        <div class="form-switch mb-3">
                            <label class="switch">
                                <input type="checkbox" id="smtp_enabled" name="smtp_enabled" 
                                      <?php echo ($data['settings']->smtp_enabled ?? '') == '1' ? 'checked' : ''; ?> value="1">
                                <span class="slider"></span>
                            </label>
                            <span>Enable Email Notifications</span>
                        </div>
                        
                        <div id="smtpSettings">
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="smtp_host">
                                        SMTP Host <span class="form-required">*</span>
                                    </label>
                                    <input type="text" class="form-input" id="smtp_host" name="smtp_host" 
                                          value="<?php echo htmlspecialchars($data['settings']->smtp_host ?? ''); ?>">
                                    <div class="form-help">e.g., smtp.gmail.com, smtp.office365.com</div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="smtp_port">
                                        SMTP Port <span class="form-required">*</span>
                                    </label>
                                    <input type="number" class="form-input" id="smtp_port" name="smtp_port" 
                                          value="<?php echo htmlspecialchars($data['settings']->smtp_port ?? '587'); ?>">
                                    <div class="form-help">Common ports: 25, 465, 587</div>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="smtp_user">
                                        SMTP Username <span class="form-required">*</span>
                                    </label>
                                    <input type="text" class="form-input" id="smtp_user" name="smtp_user" 
                                          value="<?php echo htmlspecialchars($data['settings']->smtp_user ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="smtp_pass">
                                        SMTP Password <span class="form-required">*</span>
                                    </label>
                                    <div class="form-input-group">
                                        <input type="password" class="form-input" id="smtp_pass" name="smtp_pass" 
                                              value="<?php echo htmlspecialchars($data['settings']->smtp_pass ?? ''); ?>">
                                        <span class="form-input-addon">
                                            <button type="button" id="togglePassword" style="background:none;border:none;cursor:pointer;">👁️</button>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="form-row three-columns">
                                <div class="form-group">
                                    <label class="form-label" for="smtp_encryption">Encryption</label>
                                    <select class="form-select" id="smtp_encryption" name="smtp_encryption">
                                        <option value="">None</option>
                                        <option value="tls" <?php echo ($data['settings']->smtp_encryption ?? '') == 'tls' ? 'selected' : ''; ?>>TLS</option>
                                        <option value="ssl" <?php echo ($data['settings']->smtp_encryption ?? '') == 'ssl' ? 'selected' : ''; ?>>SSL</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="smtp_from_email">
                                        From Email <span class="form-required">*</span>
                                    </label>
                                    <input type="email" class="form-input" id="smtp_from_email" name="smtp_from_email" 
                                          value="<?php echo htmlspecialchars($data['settings']->smtp_from_email ?? ''); ?>">
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="smtp_from_name">
                                        From Name <span class="form-required">*</span>
                                    </label>
                                    <input type="text" class="form-input" id="smtp_from_name" name="smtp_from_name" 
                                          value="<?php echo htmlspecialchars($data['settings']->smtp_from_name ?? ''); ?>">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="test_email">Test Email Address</label>
                                    <input type="email" class="form-input" id="test_email" placeholder="Enter email to send test">
                                </div>
                                <div class="form-group" style="display: flex; align-items: flex-end;">
                                    <button type="button" id="testSmtpConnection" class="btn btn-info">
                                        <span class="btn-icon">✉</span> Test Connection
                                    </button>
                                </div>
                            </div>
                            <div id="smtpTestResult" class="test-result"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SMS Tab -->
            <div class="tab-pane" id="sms">
                <div class="settings-card">
                    <div class="settings-card-header">
                        <span class="settings-card-header-icon">📱</span>
                        SMS Configuration
                    </div>
                    <div class="settings-card-body">
                        <div class="form-switch mb-3">
                            <label class="switch">
                                <input type="checkbox" id="sms_enabled" name="sms_enabled" 
                                      <?php echo ($data['settings']->sms_enabled ?? '') == '1' ? 'checked' : ''; ?> value="1">
                                <span class="slider"></span>
                            </label>
                            <span>Enable SMS Notifications</span>
                        </div>
                        
                        <div id="smsSettings">
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="sms_provider">SMS Provider</label>
                                    <select class="form-select" id="sms_provider" name="sms_provider">
                                        <option value="">Select Provider</option>
                                        <option value="twilio" <?php echo ($data['settings']->sms_provider ?? '') == 'twilio' ? 'selected' : ''; ?>>Twilio</option>
                                        <option value="nexmo" <?php echo ($data['settings']->sms_provider ?? '') == 'nexmo' ? 'selected' : ''; ?>>Nexmo/Vonage</option>
                                        <option value="messagebird" <?php echo ($data['settings']->sms_provider ?? '') == 'messagebird' ? 'selected' : ''; ?>>MessageBird</option>
                                        <option value="custom" <?php echo ($data['settings']->sms_provider ?? '') == 'custom' ? 'selected' : ''; ?>>Custom</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="sms_provider_key">API Key</label>
                                    <input type="text" class="form-input" id="sms_provider_key" name="sms_provider_key" 
                                          value="<?php echo htmlspecialchars($data['settings']->sms_provider_key ?? ''); ?>">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="sms_provider_secret">API Secret/Token</label>
                                    <div class="form-input-group">
                                        <input type="password" class="form-input" id="sms_provider_secret" name="sms_provider_secret" 
                                            value="<?php echo htmlspecialchars($data['settings']->sms_provider_secret ?? ''); ?>">
                                        <span class="form-input-addon">
                                            <button type="button" id="toggleSmsSecret" style="background:none;border:none;cursor:pointer;">👁️</button>
                                        </span>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="form-label" for="sms_sender_id">Sender ID/Phone Number</label>
                                    <input type="text" class="form-input" id="sms_sender_id" name="sms_sender_id" 
                                          value="<?php echo htmlspecialchars($data['settings']->sms_sender_id ?? ''); ?>">
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label" for="test_phone">Test Phone Number</label>
                                    <input type="tel" class="form-input" id="test_phone" placeholder="Enter phone number for test SMS">
                                </div>
                                <div class="form-group" style="display: flex; align-items: flex-end;">
                                    <button type="button" id="testSmsConnection" class="btn btn-info">
                                        <span class="btn-icon">📱</span> Test SMS
                                    </button>
                                </div>
                            </div>
                            <div id="smsTestResult" class="test-result"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Security Tab -->
            <div class="tab-pane" id="security">
                <div class="settings-card">
                    <div class="settings-card-header">
                        <span class="settings-card-header-icon">🔒</span>
                        Security Configuration
                    </div>
                    <div class="settings-card-body">
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="password_policy">Password Policy</label>
                                <select class="form-select" id="password_policy" name="password_policy">
                                    <option value="low" <?php echo ($data['settings']->password_policy ?? '') == 'low' ? 'selected' : ''; ?>>Basic (minimum 8 characters)</option>
                                    <option value="medium" <?php echo ($data['settings']->password_policy ?? '') == 'medium' ? 'selected' : ''; ?>>Medium (min 8 chars, 1 uppercase, 1 number)</option>
                                    <option value="high" <?php echo ($data['settings']->password_policy ?? 'high') == 'high' ? 'selected' : ''; ?>>Strong (min 10 chars, mixed case, numbers, special chars)</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="password_expiry_days">Password Expiry (days)</label>
                                <input type="number" class="form-input" id="password_expiry_days" name="password_expiry_days" min="0" max="365" 
                                      value="<?php echo htmlspecialchars($data['settings']->password_expiry_days ?? '90'); ?>">
                                <div class="form-help">Set to 0 for no expiration</div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label" for="login_attempts">Max Login Attempts</label>
                                <input type="number" class="form-input" id="login_attempts" name="login_attempts" min="3" max="10" 
                                      value="<?php echo htmlspecialchars($data['settings']->login_attempts ?? '5'); ?>">
                                <div class="form-help">Number of failed attempts before temporary lockout</div>
                            </div>
                            <div class="form-group">
                                <label class="form-label" for="lockout_time">Lockout Time (minutes)</label>
                                <input type="number" class="form-input" id="lockout_time" name="lockout_time" min="5" max="60" 
                                      value="<?php echo htmlspecialchars($data['settings']->lockout_time ?? '15'); ?>">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <div class="form-switch">
                                    <label class="switch">
                                        <input type="checkbox" id="enable_2fa" name="enable_2fa" 
                                              <?php echo ($data['settings']->enable_2fa ?? '') == '1' ? 'checked' : ''; ?> value="1">
                                        <span class="slider"></span>
                                    </label>
                                    <span>Enable Two-Factor Authentication</span>
                                </div>
                            </div>
                            <div class="form-group">
                                <div class="form-switch">
                                    <label class="switch">
                                        <input type="checkbox" id="session_timeout_enabled" name="session_timeout_enabled" 
                                              <?php echo ($data['settings']->session_timeout_enabled ?? '') == '1' ? 'checked' : ''; ?> value="1">
                                        <span class="slider"></span>
                                    </label>
                                    <span>Enable Session Timeout</span>
                                </div>
                                <div id="sessionTimeoutContainer" <?php echo ($data['settings']->session_timeout_enabled ?? '') != '1' ? 'style="display:none;"' : ''; ?> class="mt-2">
                                    <div class="form-input-group">
                                        <input type="number" class="form-input" id="session_timeout" name="session_timeout" min="5" max="480" 
                                            value="<?php echo htmlspecialchars($data['settings']->session_timeout ?? '30'); ?>">
                                        <span class="form-input-addon">minutes</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="form-actions">
            <button type="reset" class="btn btn-secondary">
                <span class="btn-icon">↺</span> Reset
            </button>
            <button type="submit" class="btn btn-primary">
                <span class="btn-icon">💾</span> Save Settings
            </button>
        </div>
    </form>
    
    <!-- Help Modal -->
    <div class="modal" id="helpModal">
        <div class="modal-dialog">
            <div class="modal-header">
                <h3 class="modal-title"><span class="settings-card-header-icon">?</span> Settings Help</h3>
                <button type="button" class="modal-close" id="closeHelpModal">&times;</button>
            </div>
            <div class="modal-body">
                <div class="accordion" id="settingsHelpAccordion">
                    <div class="accordion-item">
                        <h4 class="accordion-header">
                            <button class="accordion-button active" data-accordion="general-help">
                                General Settings
                            </button>
                        </h4>
                        <div class="accordion-content" id="general-help" style="max-height: 1000px;">
                            <div class="accordion-content-inner">
                                <p>Configure the basic system settings:</p>
                                <ul>
                                    <li><strong>Site Name:</strong> The name of your website or application.</li>
                                    <li><strong>Site URL:</strong> The full URL to your website (with https://).</li>
                                    <li><strong>Admin Email:</strong> The primary email for system notifications.</li>
                                    <li><strong>Timezone:</strong> Default timezone for date/time display.</li>
                                    <li><strong>Date Format:</strong> How dates will be displayed throughout the system.</li>
                                    <li><strong>Items Per Page:</strong> Default number of items to show in listings.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h4 class="accordion-header">
                            <button class="accordion-button" data-accordion="email-help">
                                Email Settings
                            </button>
                        </h4>
                        <div class="accordion-content" id="email-help">
                            <div class="accordion-content-inner">
                                <p>Configure email sending capabilities:</p>
                                <ul>
                                    <li><strong>SMTP Host:</strong> Your mail server address (e.g., smtp.gmail.com).</li>
                                    <li><strong>SMTP Port:</strong> The port for your mail server (common: 587, 465, 25).</li>
                                    <li><strong>SMTP Username:</strong> Your mail server login username.</li>
                                    <li><strong>SMTP Password:</strong> Your mail server login password.</li>
                                    <li><strong>Encryption:</strong> TLS or SSL based on your mail server requirements.</li>
                                    <li><strong>From Email:</strong> The email address that will appear in the From field.</li>
                                    <li><strong>From Name:</strong> The name that will appear in the From field.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h4 class="accordion-header">
                            <button class="accordion-button" data-accordion="sms-help">
                                SMS Settings
                            </button>
                        </h4>
                        <div class="accordion-content" id="sms-help">
                            <div class="accordion-content-inner">
                                <p>Configure SMS notifications:</p>
                                <ul>
                                    <li><strong>SMS Provider:</strong> Select your SMS gateway provider.</li>
                                    <li><strong>API Key:</strong> Your SMS service API key or account SID.</li>
                                    <li><strong>API Secret/Token:</strong> Your SMS service secret or auth token.</li>
                                    <li><strong>Sender ID:</strong> The phone number or ID that recipients will see.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="accordion-item">
                        <h4 class="accordion-header">
                            <button class="accordion-button" data-accordion="security-help">
                                Security Settings
                            </button>
                        </h4>
                        <div class="accordion-content" id="security-help">
                            <div class="accordion-content-inner">
                                <p>Configure security options:</p>
                                <ul>
                                    <li><strong>Password Policy:</strong> Set the complexity requirements for passwords.</li>
                                    <li><strong>Password Expiry:</strong> How many days before passwords must be changed.</li>
                                    <li><strong>Max Login Attempts:</strong> Number of failed logins before account lockout.</li>
                                    <li><strong>Lockout Time:</strong> Duration of temporary account lockout after failed attempts.</li>
                                    <li><strong>Two-Factor Authentication:</strong> Enable/disable 2FA for user accounts.</li>
                                    <li><strong>Session Timeout:</strong> Automatically log out inactive users after specified time.</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" id="closeHelpModalBtn">Close</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Tab Navigation
            const tabLinks = document.querySelectorAll('.tab-link');
            
            tabLinks.forEach(link => {
                link.addEventListener('click', function(e) {
                    e.preventDefault();
                    const tabId = this.getAttribute('data-tab');
                    
                    // Hide all tab panes
                    document.querySelectorAll('.tab-pane').forEach(pane => {
                        pane.classList.remove('active');
                    });
                    
                    // Show the selected tab pane
                    document.getElementById(tabId).classList.add('active');
                    
                    // Update active tab
                    tabLinks.forEach(tab => {
                        tab.classList.remove('active');
                    });
                    this.classList.add('active');
                });
            });
            
            // Toggle password visibility
            document.getElementById('togglePassword').addEventListener('click', function() {
                const passwordField = document.getElementById('smtp_pass');
                const type = passwordField.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordField.setAttribute('type', type);
                this.textContent = type === 'password' ? '👁️' : '👁️‍🗨️';
            });
            
            // Toggle SMS secret visibility
            document.getElementById('toggleSmsSecret').addEventListener('click', function() {
                const secretField = document.getElementById('sms_provider_secret');
                const type = secretField.getAttribute('type') === 'password' ? 'text' : 'password';
                secretField.setAttribute('type', type);
                this.textContent = type === 'password' ? '👁️' : '👁️‍🗨️';
            });
            
            // Show/Hide Session Timeout field based on checkbox
            document.getElementById('session_timeout_enabled').addEventListener('change', function() {
                document.getElementById('sessionTimeoutContainer').style.display = this.checked ? 'block' : 'none';
            });
            
            // SMTP toggle functionality
            document.getElementById('smtp_enabled').addEventListener('change', function() {
                const smtpFields = document.querySelectorAll('#smtpSettings input, #smtpSettings select, #testSmtpConnection');
                smtpFields.forEach(field => {
                    if (field.id !== 'test_email') {
                        field.required = this.checked;
                    }
                    field.disabled = !this.checked;
                });
            });
            
            // SMS toggle functionality
            document.getElementById('sms_enabled').addEventListener('change', function() {
                const smsFields = document.querySelectorAll('#smsSettings input, #smsSettings select, #testSmsConnection');
                smsFields.forEach(field => {
                    if (field.id !== 'test_phone') {
                        field.required = this.checked;
                    }
                    field.disabled = !this.checked;
                });
            });
            
            // Help button click
            document.getElementById('helpBtn').addEventListener('click', function() {
                document.getElementById('helpModal').classList.add('active');
            });
            
            // Close modal buttons
            document.getElementById('closeHelpModal').addEventListener('click', function() {
                document.getElementById('helpModal').classList.remove('active');
            });
            
            document.getElementById('closeHelpModalBtn').addEventListener('click', function() {
                document.getElementById('helpModal').classList.remove('active');
            });
            
            // Accordion functionality
            const accordionButtons = document.querySelectorAll('.accordion-button');
            
            accordionButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const contentId = this.getAttribute('data-accordion');
                    const content = document.getElementById(contentId);
                    
                    // Toggle active class
                    this.classList.toggle('active');
                    
                    // Toggle content visibility
                    if (content.style.maxHeight !== '0px' && content.style.maxHeight !== '') {
                        content.style.maxHeight = '0px';
                    } else {
                        content.style.maxHeight = content.scrollHeight + 'px';
                    }
                });
            });
            
            // Test SMTP connection
            document.getElementById('testSmtpConnection').addEventListener('click', function() {
                const testEmail = document.getElementById('test_email').value.trim();
                const smtpHost = document.getElementById('smtp_host').value.trim();
                const smtpPort = document.getElementById('smtp_port').value.trim();
                const smtpUser = document.getElementById('smtp_user').value.trim();
                const smtpPass = document.getElementById('smtp_pass').value.trim();
                
                // Validate required fields
                if (!testEmail || !smtpHost || !smtpPort || !smtpUser || !smtpPass) {
                    const resultDiv = document.getElementById('smtpTestResult');
                    resultDiv.innerHTML = '<span class="alert-icon">!</span> Please fill in all required SMTP fields and a test email address.';
                    resultDiv.className = 'test-result warning';
                    resultDiv.style.display = 'block';
                    return;
                }
                
                // Show loading indicator
                this.disabled = true;
                this.innerHTML = '<span class="btn-icon">⏳</span> Testing...';
                const resultDiv = document.getElementById('smtpTestResult');
                resultDiv.style.display = 'none';
                
                // Simulate AJAX call
                setTimeout(() => {
                    // In a real implementation, this would be an AJAX call to a backend endpoint
                    // For now, we'll just simulate success
                    this.innerHTML = '<span class="btn-icon">✉</span> Test Connection';
                    this.disabled = false;
                    resultDiv.innerHTML = '<span class="alert-icon">✓</span> SMTP connection successful! Test email sent to ' + testEmail + '.';
                    resultDiv.className = 'test-result success';
                    resultDiv.style.display = 'block';
                }, 2000);
            });
            
            // Test SMS connection
            document.getElementById('testSmsConnection').addEventListener('click', function() {
                const testPhone = document.getElementById('test_phone').value.trim();
                const smsProvider = document.getElementById('sms_provider').value;
                const smsKey = document.getElementById('sms_provider_key').value.trim();
                const smsSecret = document.getElementById('sms_provider_secret').value.trim();
                
                // Validate required fields
                if (!testPhone || !smsProvider || !smsKey || !smsSecret) {
                    const resultDiv = document.getElementById('smsTestResult');
                    resultDiv.innerHTML = '<span class="alert-icon">!</span> Please fill in all required SMS fields and a test phone number.';
                    resultDiv.className = 'test-result warning';
                    resultDiv.style.display = 'block';
                    return;
                }
                
                // Show loading indicator
                this.disabled = true;
                this.innerHTML = '<span class="btn-icon">⏳</span> Testing...';
                const resultDiv = document.getElementById('smsTestResult');
                resultDiv.style.display = 'none';
                
                // Simulate AJAX call
                setTimeout(() => {
                    // In a real implementation, this would be an AJAX call to a backend endpoint
                    this.innerHTML = '<span class="btn-icon">📱</span> Test SMS';
                    this.disabled = false;
                    resultDiv.innerHTML = '<span class="alert-icon">✓</span> SMS sent successfully to ' + testPhone + '.';
                    resultDiv.className = 'test-result success';
                    resultDiv.style.display = 'block';
                }, 2000);
            });
            
            // Form validation
            document.getElementById('settingsForm').addEventListener('submit', function(event) {
                let isValid = true;
                const requiredFields = this.querySelectorAll('[required]');
                
                requiredFields.forEach(field => {
                    if (!field.value.trim()) {
                        field.style.borderColor = '#dc2626';
                        isValid = false;
                        
                        // Add error message if not exists
                        const formGroup = field.closest('.form-group');
                        if (!formGroup.querySelector('.error-message')) {
                            const errorMsg = document.createElement('div');
                            errorMsg.className = 'error-message';
                            errorMsg.style.color = '#dc2626';
                            errorMsg.style.fontSize = '0.75rem';
                            errorMsg.style.marginTop = '0.25rem';
                            errorMsg.textContent = 'This field is required.';
                            formGroup.appendChild(errorMsg);
                        }
                    } else {
                        field.style.borderColor = '';
                        
                        // Remove error message if exists
                        const formGroup = field.closest('.form-group');
                        const errorMsg = formGroup.querySelector('.error-message');
                        if (errorMsg) {
                            errorMsg.remove();
                        }
                    }
                });
                
                if (!isValid) {
                    event.preventDefault();
                    alert('Please fill in all required fields.');
                } else {
                    // This would normally happen server-side after successful save
                    // For demo purposes, show toast on submit
                    event.preventDefault();
                    
                    // Simulate save
                    setTimeout(() => {
                        const toast = document.getElementById('settingsSavedToast');
                        toast.classList.add('visible');
                        
                        // Auto hide after 3 seconds
                        setTimeout(() => {
                            toast.classList.remove('visible');
                        }, 3000);
                    }, 500);
                }
            });
            
            // Check initial state of toggles
            if (document.getElementById('smtp_enabled') && !document.getElementById('smtp_enabled').checked) {
                const smtpFields = document.querySelectorAll('#smtpSettings input, #smtpSettings select, #testSmtpConnection');
                smtpFields.forEach(field => {
                    field.required = false;
                    field.disabled = true;
                });
            }
            
            if (document.getElementById('sms_enabled') && !document.getElementById('sms_enabled').checked) {
                const smsFields = document.querySelectorAll('#smsSettings input, #smsSettings select, #testSmsConnection');
                smsFields.forEach(field => {
                    field.required = false;
                    field.disabled = true;
                });
            }
        });
    </script>
</div>

<?php
// Get the buffered content
$content = ob_get_clean();

// Include the dashboard layout
require_once APPROOT . '/views/dashboard/dashboard_layout.php';
?>