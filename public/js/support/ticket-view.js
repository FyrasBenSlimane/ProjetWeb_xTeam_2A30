/**
 * Ticket View Enhancements
 * Improves UI/UX for the ticket view page
 */

// Define BASE_URL if not already defined
if (typeof BASE_URL === 'undefined') {
    window.BASE_URL = window.location.origin;
}

document.addEventListener('DOMContentLoaded', function () {
    initializeActionButtons();
    initializeReplyForm();
    setupQuoteReply();
    enhanceAttachmentDisplay();
});

/**
 * Initialize action buttons for ticket status changes
 */
function initializeActionButtons() {
    // Fix dropdown menu issues
    const dropdownButton = document.getElementById('ticketActionsDropdown');
    if (dropdownButton) {
        // Make sure bootstrap is properly initializing the dropdown
        if (typeof bootstrap !== 'undefined' && !dropdownButton.hasAttribute('data-initialized')) {
            new bootstrap.Dropdown(dropdownButton);
            dropdownButton.setAttribute('data-initialized', 'true');
        }
    }

    // Add loading state to status change links
    const statusChangeLinks = document.querySelectorAll('.dropdown-item:not(.text-danger)');
    statusChangeLinks.forEach(link => {
        link.addEventListener('click', function (e) {
            // Don't block navigation, just show loading
            const originalContent = this.innerHTML;
            this.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Updating...';

            // Disable pointer events during loading
            this.style.pointerEvents = 'none';

            // Hide dropdown
            const dropdown = bootstrap.Dropdown.getInstance(dropdownButton);
            if (dropdown) dropdown.hide();
        });
    });

    // Add confirmation to delete button
    const deleteLink = document.querySelector('.dropdown-item.text-danger');
    if (deleteLink) {
        deleteLink.removeAttribute('onclick');
        deleteLink.addEventListener('click', function (e) {
            e.preventDefault();

            if (confirm('Are you sure you want to delete this ticket? This action cannot be undone.')) {
                // Show loading state
                this.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Deleting...';

                // Disable pointer events during loading
                this.style.pointerEvents = 'none';

                // Navigate to the delete URL
                window.location = this.getAttribute('href');
            }
        });
    }
}

/**
 * Initialize reply form with enhanced functionality
 */
function initializeReplyForm() {
    const replyForm = document.querySelector('.reply-form form');
    const textarea = document.querySelector('.reply-form textarea');

    if (!replyForm || !textarea) return;

    // Automatically resize textarea as user types
    textarea.setAttribute('style', 'height:' + (textarea.scrollHeight) + 'px;overflow-y:hidden;');
    textarea.addEventListener('input', function () {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    // Add validation and loading state to the form
    replyForm.addEventListener('submit', function (e) {
        const submitButton = this.querySelector('button[type="submit"]');

        // Basic validation
        if (!textarea.value.trim()) {
            e.preventDefault();

            // Highlight textarea with error
            textarea.classList.add('is-invalid');

            // Show error message
            let errorMsg = document.querySelector('.reply-error-message');
            if (!errorMsg) {
                errorMsg = document.createElement('div');
                errorMsg.className = 'invalid-feedback reply-error-message';
                errorMsg.textContent = 'Please enter a reply message';
                textarea.after(errorMsg);
            }

            return;
        }

        // Show loading state on submit button
        submitButton.innerHTML = '<i class="bx bx-loader-alt bx-spin"></i> Sending...';
        submitButton.disabled = true;

        // Remove invalid status if it was previously added
        textarea.classList.remove('is-invalid');
    });

    // Clear error state when typing
    textarea.addEventListener('input', function () {
        this.classList.remove('is-invalid');
        const errorMsg = document.querySelector('.reply-error-message');
        if (errorMsg) errorMsg.remove();
    });

    // Make "Mark as Answered" checkbox more visually appealing
    const checkbox = document.getElementById('markAsAnswered');
    if (checkbox) {
        checkbox.addEventListener('change', function () {
            const label = this.nextElementSibling;
            if (this.checked) {
                label.innerHTML = '<i class="bx bx-check-circle"></i> Will be marked as Answered';
                label.style.color = 'var(--support-primary)';
            } else {
                label.innerHTML = '<i class="bx bx-check"></i> Mark as Answered';
                label.style.color = '';
            }
        });
    }
}

/**
 * Setup quote reply functionality
 */
function setupQuoteReply() {
    // Add quote buttons to messages
    const messageBodyElements = document.querySelectorAll('.support-message-body');
    const replyTextarea = document.querySelector('.reply-form textarea');

    if (!messageBodyElements.length || !replyTextarea) return;

    messageBodyElements.forEach((element, index) => {
        const quoteBtn = document.createElement('button');
        quoteBtn.type = 'button';
        quoteBtn.className = 'quote-btn';
        quoteBtn.innerHTML = '<i class="bx bx-quote-right"></i>';
        quoteBtn.title = 'Quote this message';

        element.parentElement.appendChild(quoteBtn);

        // Add click event
        quoteBtn.addEventListener('click', function () {
            const messageText = element.textContent.trim();
            const sender = element.closest('.support-message').querySelector('.support-message-name').textContent.trim();

            // Format the quote
            let quote = `> ${sender} wrote:\n`;
            messageText.split('\n').forEach(line => {
                quote += `> ${line}\n`;
            });
            quote += '\n';

            // Insert at cursor position or append
            insertTextAtCursor(replyTextarea, quote);

            // Scroll to and focus on textarea
            replyTextarea.scrollIntoView({ behavior: 'smooth' });
            setTimeout(() => replyTextarea.focus(), 500);
        });
    });
}

/**
 * Insert text at cursor position in textarea
 * @param {HTMLElement} textarea - The textarea element
 * @param {string} text - Text to insert
 */
function insertTextAtCursor(textarea, text) {
    const startPos = textarea.selectionStart;
    const endPos = textarea.selectionEnd;
    const currentValue = textarea.value;

    textarea.value = currentValue.substring(0, startPos) +
        text +
        currentValue.substring(endPos);

    // Move cursor position after inserted text
    const newPos = startPos + text.length;
    textarea.setSelectionRange(newPos, newPos);

    // Trigger input event to resize textarea
    const event = new Event('input', { bubbles: true });
    textarea.dispatchEvent(event);
}

/**
 * Enhance attachment display with previews
 */
function enhanceAttachmentDisplay() {
    const attachmentLinks = document.querySelectorAll('.attachment-link');

    attachmentLinks.forEach(link => {
        const filePath = link.getAttribute('href');
        const fileName = link.textContent.trim();

        // Determine if it's an image
        const fileExtension = fileName.split('.').pop().toLowerCase();
        const isImage = ['jpg', 'jpeg', 'png', 'gif', 'svg', 'webp'].includes(fileExtension);

        // For images, add a thumbnail preview
        if (isImage) {
            // Add an image icon to the link
            link.querySelector('i').className = 'bx bx-image';

            // Create a preview button
            const previewBtn = document.createElement('button');
            previewBtn.className = 'attachment-preview-btn';
            previewBtn.innerHTML = '<i class="bx bx-show"></i> Preview';
            previewBtn.type = 'button';

            // Insert after link
            link.parentNode.insertBefore(previewBtn, link.nextSibling);

            // Add click event to preview button
            previewBtn.addEventListener('click', function () {
                const modal = document.createElement('div');
                modal.className = 'attachment-preview-modal';
                modal.innerHTML = `
                    <div class="modal-content">
                        <span class="close">&times;</span>
                        <img src="${filePath}" alt="${fileName}" class="preview-image">
                        <div class="preview-caption">${fileName}</div>
                    </div>
                `;

                document.body.appendChild(modal);

                // Add close handler
                const closeBtn = modal.querySelector('.close');
                closeBtn.addEventListener('click', () => {
                    modal.remove();
                });

                // Close on click outside
                modal.addEventListener('click', function (e) {
                    if (e.target === modal) {
                        modal.remove();
                    }
                });
            });
        } else {
            // Adjust icon based on file type
            let fileIcon = 'bx-file';

            if (['pdf'].includes(fileExtension)) fileIcon = 'bx-file-pdf';
            else if (['doc', 'docx'].includes(fileExtension)) fileIcon = 'bx-file-doc';
            else if (['xls', 'xlsx'].includes(fileExtension)) fileIcon = 'bx-spreadsheet';
            else if (['zip', 'rar', '7z'].includes(fileExtension)) fileIcon = 'bx-archive';
            else if (['txt', 'md'].includes(fileExtension)) fileIcon = 'bx-file-txt';

            link.querySelector('i').className = `bx ${fileIcon}`;
        }
    });
}

/**
 * Add smooth scrolling animation for all anchor links
 */
function setupSmoothScrolling() {
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');

            if (targetId === '#' || !targetId) return;

            const targetElement = document.querySelector(targetId);

            if (targetElement) {
                e.preventDefault();
                targetElement.scrollIntoView({
                    behavior: 'smooth'
                });
            }
        });
    });
}