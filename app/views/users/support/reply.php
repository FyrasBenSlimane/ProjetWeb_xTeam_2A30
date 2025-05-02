<?php require APPROOT . '/views/layouts/header.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb bg-light p-2">
                    <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>">Home</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/support">Support Tickets</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo URLROOT; ?>/support/view/<?php echo $data['ticket_id']; ?>">Ticket #<?php echo $data['ticket_id']; ?></a></li>
                    <li class="breadcrumb-item active">Reply</li>
                </ol>
            </nav>

            <div class="card shadow-sm">
                <div class="card-header">
                    <h5 class="mb-0">Reply to Ticket #<?php echo $data['ticket_id']; ?></h5>
                </div>
                <div class="card-body">
                    <form action="<?php echo URLROOT; ?>/support/reply/<?php echo $data['ticket_id']; ?>" method="post">
                        <div class="mb-3">
                            <label for="message" class="form-label">Your Reply <span class="text-danger">*</span></label>
                            <textarea class="form-control <?php echo (!empty($data['message_err'])) ? 'is-invalid' : ''; ?>"
                                id="message" name="message" rows="6"
                                placeholder="Type your message here..."><?php echo $data['message']; ?></textarea>
                            <div class="invalid-feedback"><?php echo $data['message_err']; ?></div>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?php echo URLROOT; ?>/support/view/<?php echo $data['ticket_id']; ?>" class="btn btn-light">Cancel</a>
                            <button type="submit" class="btn btn-primary">Submit Reply</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header">
                    <h6 class="mb-0">Original Ticket Details</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <h6 class="text-muted">Subject</h6>
                        <p><?php echo htmlspecialchars($data['ticket']->subject); ?></p>
                    </div>

                    <div class="mb-0">
                        <h6 class="text-muted">Description</h6>
                        <div class="p-3 bg-light rounded">
                            <?php echo nl2br(htmlspecialchars($data['ticket']->description)); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/layouts/footer.php'; ?>