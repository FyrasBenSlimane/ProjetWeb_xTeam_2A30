/**
 * GitHub Authentication Helper Functions
 * 
 * This file contains functions for handling GitHub OAuth authentication
 * in a popup window and communicating with the parent window.
 */

/**
 * Opens a GitHub authentication popup window
 * 
 * @param {string} action - Authentication action ('login' or 'register')
 * @param {string} baseUrl - The base URL of the application
 */
function openGitHubAuth(action, baseUrl) {
    // Default to login if not specified
    action = action || 'login';
    
    // Open GitHub auth in a popup window
    const width = 600;
    const height = 700;
    const left = (window.innerWidth - width) / 2;
    const top = (window.innerHeight - height) / 2;
    
    const popup = window.open(`${baseUrl}/users/githubAuth?auth_action=${action}`, 'github-auth', 
        `width=${width},height=${height},left=${left},top=${top},resizable=yes,scrollbars=yes`);
    
    // Focus the popup
    if (popup) {
        popup.focus();
        
        // Set up a check in case popup is closed manually
        const checkPopupClosed = setInterval(() => {
            if (popup.closed) {
                clearInterval(checkPopupClosed);
                console.log("GitHub auth popup was closed manually");
            }
        }, 1000);
    } else {
        alert("Please allow popups for this website to use GitHub authentication.");
    }
}

/**
 * Sets up a listener for messages from the GitHub authentication popup
 * 
 * @param {string} baseUrl - The base URL of the application
 */
function setupGitHubAuthListener(baseUrl) {
    // Remove any existing message listeners to prevent duplicates
    if (window.githubMessageListener) {
        window.removeEventListener('message', window.githubMessageListener);
    }
    
    // Create and store the message listener function
    window.githubMessageListener = function(event) {
        console.log("Received postMessage event:", event);
        
        // For debugging: log relevant data
        console.log("Event origin:", event.origin);
        console.log("Window location origin:", window.location.origin);
        console.log("Event data:", event.data);
        
        // Check if the data has the expected structure
        if (!event.data || typeof event.data !== 'object') {
            console.log("Event data is not an object:", event.data);
            return;
        }
        
        // Validate message is from GitHub auth popup
        if (!event.data.source || event.data.source !== 'github-auth') {
            console.log("Not from GitHub auth popup:", event.data);
            return;
        }
        
        console.log("Processing GitHub auth message:", event.data);
        
        // Handle authentication result
        if (event.data.auth === 'success') {
            // Display optional message (for existing email accounts)
            if (event.data.message) {
                showGitHubMessage(event.data.message, 'success');
            }
            
            // Check if this is a registration that needs profile completion
            if (event.data.message && event.data.message.includes("Please complete your profile")) {
                console.log("Registration needs profile completion, redirecting to:", event.data.redirect);
                // This is a registration that needs profile completion, redirect to the completion page
                setTimeout(() => {
                    window.location.href = event.data.redirect || `${baseUrl}/users/auth?action=github_complete`;
                }, 1000);
                return;
            }
            
            // Standard login flow
            if (event.data.email) {
                // Use email to complete login
                console.log("Email found in message, completing login:", event.data.email);
                completeLogin(event.data.email, baseUrl);
            } else {
                console.log("No email found in message, redirecting to provided URL");
                // No email but redirect provided, use it after a short delay
                setTimeout(() => {
                    if (event.data.redirect) {
                        console.log("GitHub auth successful, redirecting to:", event.data.redirect);
                        window.location.href = event.data.redirect;
                    } else {
                        // Fallback if no redirect URL provided
                        window.location.href = `${baseUrl}/pages/index?authenticated=true`;
                    }
                }, 1000);
            }
        } else if (event.data.auth === 'error') {
            // Display error message
            const errorMsg = event.data.message || 'GitHub authentication failed';
            console.log("GitHub auth error:", errorMsg);
            displayGitHubAuthError(errorMsg);
        }
    };
    
    // Add the message listener
    window.addEventListener('message', window.githubMessageListener);
    
    console.log("GitHub auth listener set up successfully");
}

/**
 * Completes the login process by making an AJAX request to the server
 * 
 * @param {string} email - The user's email address
 * @param {string} baseUrl - The base URL of the application
 */
function completeLogin(email, baseUrl) {
    console.log(`Completing login for email: ${email}`);
    
    // Show loading indicator
    showGitHubMessage('Completing login...', 'success');
    
    // Make AJAX request to complete login
    fetch(`${baseUrl}/users/completeGithubLogin`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        },
        body: JSON.stringify({ email: email })
    })
    .then(response => {
        console.log("Server response:", response);
        
        // Check if response is ok (status in the range 200-299)
        if (!response.ok) {
            // Log more details about the failed response
            console.error("Response not OK", {
                status: response.status,
                statusText: response.statusText
            });
            
            // Try to get response text for debugging
            return response.text().then(text => {
                console.error("Response text (first 200 chars):", text.substring(0, 200));
                // If it's HTML (likely a redirect to login page or error page)
                if (text.trim().startsWith('<!DOCTYPE') || text.trim().startsWith('<html')) {
                    throw new Error(`Server returned HTML instead of JSON. You may have been logged out or there was a server error.`);
                }
                
                // Check if the response is JSON despite the error
                try {
                    return JSON.parse(text);
                } catch (e) {
                    // If it's not JSON, throw an error with the text content
                    throw new Error(`Server returned non-JSON response: ${text.substring(0, 100)}...`);
                }
            });
        }
        
        // Get content type to check if it's actually JSON
        const contentType = response.headers.get('content-type');
        if (contentType && contentType.includes('application/json')) {
            return response.json();
        } else {
            // Not JSON, handle as text
            return response.text().then(text => {
                console.error("Unexpected content type:", contentType);
                console.error("Response text (first 200 chars):", text.substring(0, 200));
                
                // If it's HTML (likely a redirect to login page or error page)
                if (text.trim().startsWith('<!DOCTYPE') || text.trim().startsWith('<html')) {
                    throw new Error(`Server returned HTML instead of JSON. You may have been redirected to another page.`);
                }
                
                // Try to parse as JSON anyway as a fallback
                try {
                    return JSON.parse(text);
                } catch (e) {
                    throw new Error(`Expected JSON but got: ${text.substring(0, 100)}...`);
                }
            });
        }
    })
    .then(data => {
        console.log('Login completed successfully:', data);
        if (data.success) {
            if (data.redirect) {
                window.location.href = data.redirect;
            } else {
                window.location.href = `${baseUrl}/pages/index?authenticated=true`;
            }
        } else {
            // Handle application-level errors
            throw new Error(data.message || 'Login failed');
        }
    })
    .catch(error => {
        console.error('Error completing login:', error);
        // Display user-friendly error message
        displayGitHubAuthError(error.message || 'Failed to complete login. Please try again or contact support.');
        
        // Give user option to try again
        setTimeout(() => {
            const authContainer = document.querySelector('.auth-form-container');
            if (authContainer) {
                const tryAgainButton = document.createElement('button');
                tryAgainButton.className = 'btn btn-secondary mt-3';
                tryAgainButton.textContent = 'Try Again';
                tryAgainButton.onclick = () => window.location.reload();
                
                // Find error message container
                const errorContainer = authContainer.querySelector('.alert-danger');
                if (errorContainer) {
                    errorContainer.appendChild(document.createElement('br'));
                    errorContainer.appendChild(tryAgainButton);
                }
            }
        }, 1000);
    });
}

/**
 * Displays a success message from GitHub authentication
 * 
 * @param {string} message - The message to display
 * @param {string} type - The type of message ('success' or 'error')
 */
function showGitHubMessage(message, type = 'success') {
    console.log(`Displaying ${type} message:`, message);
    
    // Create a styled alert box
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type === 'success' ? 'success' : 'danger'} auth-alert`;
    alertDiv.textContent = message;
    
    // Find where to display the alert
    const alertContainer = document.querySelector('.auth-form-container');
    if (!alertContainer) {
        // If no container found, use a regular alert
        console.log("No alert container found, using browser alert");
        alert(message);
        return;
    }
    
    const existingAlert = alertContainer.querySelector('.auth-alert');
    
    // Replace existing alert or add at the top
    if (existingAlert) {
        alertContainer.replaceChild(alertDiv, existingAlert);
    } else {
        alertContainer.insertBefore(alertDiv, alertContainer.firstChild);
    }
    
    // Scroll to top to make sure the alert is visible
    window.scrollTo(0, 0);
    
    // Auto-dismiss success messages after 5 seconds
    if (type === 'success') {
        setTimeout(() => {
            if (alertDiv.parentNode) {
                // Fade out effect
                alertDiv.style.transition = 'opacity 0.5s ease';
                alertDiv.style.opacity = '0';
                
                // Remove after fade completes
                setTimeout(() => {
                    if (alertDiv.parentNode) {
                        alertDiv.parentNode.removeChild(alertDiv);
                    }
                }, 500);
            }
        }, 5000);
    }
}

/**
 * Displays an error message from GitHub authentication
 * 
 * @param {string} errorMessage - The error message to display
 */
function displayGitHubAuthError(errorMessage) {
    showGitHubMessage(errorMessage, 'error');
}

// Initialize GitHub authentication listeners when the document is ready
document.addEventListener('DOMContentLoaded', function() {
    // Get the base URL from a meta tag or global variable or default to window.location.origin
    const baseUrl = document.querySelector('meta[name="root-url"]')?.content || window.URL_ROOT || window.location.origin + '/web';
    
    // Set up the message listener
    setupGitHubAuthListener(baseUrl);
    
    console.log("GitHub auth event listeners initialized on page load");
});
