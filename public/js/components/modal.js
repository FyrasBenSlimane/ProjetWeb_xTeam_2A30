/**
 * Global Modal Functionality
 * This script provides consistent modal behavior across the application
 */

document.addEventListener('DOMContentLoaded', function() {
    // Function to open a modal
    window.openModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;
        
        // Skip Bootstrap modals (they use their own system)
        if (modal.classList.contains('modal') && modal.classList.contains('fade')) {
            // Let Bootstrap handle its own modals
            return;
        }
        
        // Handle our custom modal types
        if (modal.classList.contains('modal-overlay')) {
            modal.classList.add('active');
        } else if (modal.classList.contains('modal') && !modal.classList.contains('fade')) {
            modal.classList.add('show');
        }
        
        // Prevent body scrolling
        document.body.style.overflow = 'hidden';
    };
    
    // Function to close a modal
    window.closeModal = function(modalId) {
        const modal = document.getElementById(modalId);
        if (!modal) return;
        
        // Skip Bootstrap modals (they use their own system)
        if (modal.classList.contains('modal') && modal.classList.contains('fade')) {
            // Let Bootstrap handle its own modals
            return;
        }
        
        // Handle our custom modal types
        if (modal.classList.contains('modal-overlay')) {
            modal.classList.remove('active');
        } else if (modal.classList.contains('modal') && !modal.classList.contains('fade')) {
            modal.classList.remove('show');
        }
        
        // Restore scrolling
        document.body.style.overflow = '';
    };
    
    // Close modals when clicking outside
    document.addEventListener('click', function(e) {
        // For modal-overlay style modals
        document.querySelectorAll('.modal-overlay.active').forEach(modal => {
            if (e.target === modal) {
                modal.classList.remove('active');
                document.body.style.overflow = '';
            }
        });
        
        // For custom modals (not Bootstrap)
        document.querySelectorAll('.modal.show:not(.fade)').forEach(modal => {
            if (e.target === modal) {
                modal.classList.remove('show');
                document.body.style.overflow = '';
            }
        });
    });
      // Close modals with Escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape') {
            // Close all active custom modals (not Bootstrap)
            document.querySelectorAll('.modal-overlay.active, .modal.show:not(.fade)').forEach(modal => {
                if (modal.classList.contains('modal-overlay')) {
                    modal.classList.remove('active');
                } else if (modal.classList.contains('modal') && !modal.classList.contains('fade')) {
                    modal.classList.remove('show');
                }
                document.body.style.overflow = '';
            });
        }
    });
    
    // Initialize close buttons for custom modals
    document.querySelectorAll('.modal-close, [data-dismiss="modal"]:not([data-bs-dismiss="modal"])').forEach(button => {
        button.addEventListener('click', function() {
            let modal = this.closest('.modal-overlay, .modal:not(.fade)');
            if (modal) {
                if (modal.classList.contains('modal-overlay')) {
                    modal.classList.remove('active');
                } else if (modal.classList.contains('modal') && !modal.classList.contains('fade')) {
                    modal.classList.remove('show');
                }
                document.body.style.overflow = '';
            }
        });
    });
    
    // Add a helper to check for Bootstrap modals
    window.isBootstrapModal = function(modalId) {
        const modal = document.getElementById(modalId);
        return modal && modal.classList.contains('modal') && modal.classList.contains('fade');
    };
}); 