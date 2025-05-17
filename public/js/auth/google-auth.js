/**
 * Google Authentication Module
 * 
 * Handles Google OAuth authentication flow and communicates with the server.
 * This implementation uses a popup window to handle the OAuth flow.
 */

// Google client ID from configuration
const googleClientId = '784347061118-rfdt59vvfdfeob11oo11f76ipgn0cspl.apps.googleusercontent.com';

/**
 * Opens the Google OAuth authorization popup window
 * 
 * @param {string} action - Either 'login' or 'register'
 * @param {string} rootUrl - The root URL of the application
 */
function openGoogleAuth(action, rootUrl) {
    // Create and show status element for feedback
    createStatusElement();
    const statusElement = document.getElementById('auth-status');
    if (statusElement) {
        statusElement.textContent = 'Connecting to Google...';
        statusElement.className = 'alert alert-info';
        statusElement.style.display = 'block';
    }
    
    // Open the popup window directly to our server's Google auth endpoint
    const width = 600;
    const height = 700;
    const left = window.screenX + (window.outerWidth - width) / 2;
    const top = window.screenY + (window.outerHeight - height) / 2.5;
    
    // Store the action in localStorage so we know what to do after auth
    localStorage.setItem('google_auth_action', action);
    
    // Open the popup directly to our server endpoint
    window.googleAuthWindow = window.open(
        `${rootUrl}/users/googleAuth?auth_action=${action}`,
        'Google OAuth',
        `width=${width},height=${height},left=${left},top=${top}`
    );
    
    console.log('Google Auth window opened');
    
    // Monitor popup to detect if it's closed by user
    const popupMonitor = setInterval(() => {
        if (window.googleAuthWindow && window.googleAuthWindow.closed) {
            clearInterval(popupMonitor);
            console.log('Google Auth window was closed by user');
            // Provide feedback if the window was closed
            if (statusElement) {
                statusElement.textContent = 'Authentication cancelled';
                statusElement.className = 'alert alert-warning';
            }
        }
    }, 1000);
}

/**
 * Setup event listener for Google OAuth popup window communication
 * 
 * @param {string} rootUrl - The root URL of the application
 */
function setupGoogleAuthListener(rootUrl) {
    // Remove any existing message listeners to prevent duplicates
    if (window.googleMessageListener) {
        window.removeEventListener('message', window.googleMessageListener);
    }
    
    // Create and store the message listener function
    window.googleMessageListener = function(event) {
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
        
        // Validate message is from Google auth popup
        if (!event.data.source || event.data.source !== 'google-auth') {
            console.log("Not from Google auth popup:", event.data);
            return;
        }
        
        console.log("Processing Google auth message:", event.data);
        
        // Create status element if it doesn't exist
        createStatusElement();
        const statusElement = document.getElementById('auth-status');
        
        // Handle authentication result
        if (event.data.auth === 'success') {
            // Display optional message (for existing email accounts)
            if (event.data.message) {
                showGoogleMessage(event.data.message, 'success');
            }
            
            // Check if this is a registration that needs profile completion
            if (event.data.message && event.data.message.includes("Please complete your profile")) {
                console.log("Registration needs profile completion, redirecting to:", event.data.redirect);
                // This is a registration that needs profile completion, redirect to the completion page
                setTimeout(() => {
                    window.location.href = event.data.redirect || `${rootUrl}/users/auth?action=google_complete`;
                }, 1000);
                return;
            }
            
            // Standard login flow
            if (event.data.email) {
                // Use email to complete login
                console.log("Email found in message, completing login:", event.data.email);
                completeLogin(event.data.email, rootUrl);
            } else {
                console.log("No email found in message, redirecting to provided URL");
                // No email but redirect provided, use it after a short delay
                setTimeout(() => {
                    if (event.data.redirect) {
                        console.log("Google auth successful, redirecting to:", event.data.redirect);
                        window.location.href = event.data.redirect;
                    } else {
                        // Fallback if no redirect URL provided
                        window.location.href = `${rootUrl}/pages/index?authenticated=true`;
                    }
                }, 1000);
            }
        } else if (event.data.auth === 'error') {
            // Display error message
            const errorMsg = event.data.message || 'Google authentication failed';
            console.log("Google auth error:", errorMsg);
            displayGoogleAuthError(errorMsg);
        }
    };
    
    // Add the message listener
    window.addEventListener('message', window.googleMessageListener);
    
    console.log("Google auth listener set up successfully");
}

/**
 * Completes the login process by making an AJAX request to the server
 * 
 * @param {string} email - The user's email address
 * @param {string} rootUrl - The root URL of the application
 */
function completeLogin(email, rootUrl) {
    console.log(`Completing login for email: ${email}`);
    
    // Show loading indicator
    showGoogleMessage('Completing login...', 'success');
    
    // Make AJAX request to complete login
    fetch(`${rootUrl}/users/completeGoogleLogin`, {
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
                // Redirect to the specified URL
                console.log('Redirecting to:', data.redirect);
                window.location.href = data.redirect;
            } else {
                // Fallback redirect
                window.location.href = `${rootUrl}/pages/index?authenticated=true`;
            }
        } else {
            // Handle application-level errors
            throw new Error(data.message || 'Login failed');
        }
    })
    .catch(error => {
        console.error('Error completing login:', error);
        
        // Display user-friendly error message
        displayGoogleAuthError(error.message || 'Failed to complete login. Please try again or contact support.');
        
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
 * Displays a success message from Google authentication
 * 
 * @param {string} message - The message to display
 * @param {string} type - The type of message ('success' or 'error')
 */
function showGoogleMessage(message, type = 'success') {
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
 * Displays an error message from Google authentication
 * 
 * @param {string} errorMessage - The error message to display
 */
function displayGoogleAuthError(errorMessage) {
    showGoogleMessage(errorMessage, 'error');
}

/**
 * Create status element to display authentication messages
 */
function createStatusElement() {
    if (!document.getElementById('auth-status')) {
        const statusElement = document.createElement('div');
        statusElement.id = 'auth-status';
        statusElement.className = 'alert';
        statusElement.style.display = 'none';
        statusElement.style.marginTop = '1rem';
        
        // Find a good place to add it - try various selectors
        const authForm = document.querySelector('.auth-form') || 
                       document.querySelector('form') ||
                       document.querySelector('.auth-card');
        
        if (authForm) {
            // Try to find the social login section
            const socialSection = document.querySelector('.social-login') || 
                                document.querySelector('.social-buttons');
            
            if (socialSection) {
                // Insert after social login section
                socialSection.after(statusElement);
            } else {
                // Otherwise append to the form
                authForm.appendChild(statusElement);
            }
        } else {
            // Last resort, add to body
            document.body.appendChild(statusElement);
        }
    }
}

// Initialize Google authentication listeners when the document is ready
document.addEventListener('DOMContentLoaded', function() {
    // Get the root URL from meta tag or default to window location with /web
    const rootUrl = document.querySelector('meta[name="root-url"]')?.content || 
                  (window.location.origin + '/web');
    
    // Set up the message listener
    setupGoogleAuthListener(rootUrl);
    
    console.log("Google auth event listeners initialized on page load");
});