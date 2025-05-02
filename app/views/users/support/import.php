<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="container py-5">
    <div class="row mb-4">
        <div class="col-lg-8 mx-auto">
            <nav aria-label="breadcrumb" class="support-breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/support">Support Center</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Import Tickets</li>
                </ol>
            </nav>

            <!-- Flash Message -->
            <?php flash('ticket_message'); ?>

            <div class="card border-0 shadow-sm mb-4">
                <div class="gradient-header">
                    <h4 class="m-0"><i class="fas fa-file-import me-2"></i> Import Support Tickets</h4>
                </div>
                <div class="card-body p-4">
                    <div class="alert alert-info border-left-info mb-4" role="alert">
                        <h5 class="alert-heading"><i class="fas fa-info-circle me-2"></i> Bulk Import Instructions</h5>
                        <p class="mb-2">Upload a CSV or Excel file containing multiple support tickets to import them all at once.</p>
                        <ul class="mb-0">
                            <li>Your file must include columns for <strong>subject</strong>, <strong>description</strong>, <strong>category</strong>, and <strong>priority</strong></li>
                            <li>Supported file formats: <strong>.csv</strong>, <strong>.xlsx</strong></li>
                            <li>Maximum file size: <strong>10MB</strong></li>
                            <li>Up to 100 tickets per import</li>
                        </ul>
                    </div>

                    <form action="<?php echo URLROOT; ?>/support/import" method="POST" enctype="multipart/form-data" class="needs-validation" novalidate>
                        <div class="mb-4">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <label for="import_file" class="form-label fw-bold">Upload File</label>
                                <a href="<?php echo URLROOT; ?>/support/downloadTemplate" class="text-decoration-none">
                                    <i class="fas fa-download me-1"></i> Download Template
                                </a>
                            </div>
                            <div class="input-group mb-3">
                                <input type="file" class="form-control" id="import_file" name="import_file" accept=".csv, .xlsx" required>
                                <label class="input-group-text" for="import_file">Upload</label>
                            </div>
                            <div class="form-text text-muted">Select a CSV or Excel file with your support tickets data.</div>
                        </div>

                        <div class="border rounded p-3 mb-4 bg-light">
                            <h6 class="mb-3">File Format Example</h6>
                            <div class="table-responsive">
                                <table class="table table-sm table-bordered mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>subject</th>
                                            <th>description</th>
                                            <th>category</th>
                                            <th>priority</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Login Issue</td>
                                            <td>I can't login to my account...</td>
                                            <td>technical</td>
                                            <td>high</td>
                                        </tr>
                                        <tr>
                                            <td>Billing Question</td>
                                            <td>I was charged twice for...</td>
                                            <td>billing</td>
                                            <td>medium</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <div class="form-text mt-2">
                                <small>
                                    <strong>Categories:</strong> technical, billing, account, feature, other<br>
                                    <strong>Priorities:</strong> high, medium, low
                                </small>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <a href="<?php echo URLROOT; ?>/support" class="btn btn-light me-md-2">
                                <i class="fas fa-arrow-left me-1"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-upload me-1"></i> Import Tickets
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Import Tips Section -->
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-light">
                    <h5 class="mb-0"><i class="fas fa-lightbulb text-warning me-2"></i> Import Tips</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="fw-bold">Preparing Your File</h6>
                            <ul class="mb-4">
                                <li>Ensure your column names match exactly (case-sensitive)</li>
                                <li>Remove any special formatting from Excel files</li>
                                <li>Descriptions can include multiple lines of text</li>
                                <li>For CSV files, use comma (,) as the delimiter</li>
                            </ul>
                        </div>
                        <div class="col-md-6">
                            <h6 class="fw-bold">Common Issues</h6>
                            <ul>
                                <li>Check for missing required columns</li>
                                <li>Verify that category values are valid</li>
                                <li>Priority must be 'high', 'medium', or 'low'</li>
                                <li>Make sure CSV is properly formatted (UTF-8 encoding recommended)</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Import validation and preview script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Form validation
    const form = document.querySelector('.needs-validation');
    
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
        }
        form.classList.add('was-validated');
    });

    // File input change handler
    const fileInput = document.getElementById('import_file');
    
    fileInput.addEventListener('change', function() {
        const file = this.files[0];
        if (file) {
            const fileSize = file.size / 1024 / 1024; // Convert to MB
            const fileType = file.name.split('.').pop().toLowerCase();
            
            // Validate file size
            if (fileSize > 10) {
                alert('File is too large. Maximum size is 10MB.');
                this.value = '';
                return;
            }
            
            // Validate file type
            if (fileType !== 'csv' && fileType !== 'xlsx') {
                alert('Invalid file type. Please select a CSV or Excel file.');
                this.value = '';
                return;
            }
        }
    });
});
</script>

<?php require APPROOT . '/views/layouts/footer.php'; ?>