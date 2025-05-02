<?php
class Application {
    protected $currentController = 'PagesController';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct() {
        // Check for "Remember Me" cookie and auto-login user if valid
        $this->handleRememberMeCookie();
    }

    /**
     * Handle the "Remember Me" cookie for auto-login
     */
    private function handleRememberMeCookie() {
        // If user is not logged in and remember cookie exists
        if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_user'])) {
            // Get the cookie value and split into user_id and token
            list($userId, $token) = explode(':', $_COOKIE['remember_user']);
            
            // If we have both parts
            if ($userId && $token) {
                // Load User model
                $userModel = $this->loadModel('User');
                
                // Verify token
                $user = $userModel->verifyRememberToken($userId, $token);
                
                if ($user) {
                    // Create user session
                    $_SESSION['user_id'] = $user->id;
                    $_SESSION['user_email'] = $user->email;
                    $_SESSION['user_name'] = $user->name;
                    $_SESSION['user_account_type'] = $user->account_type;
                    
                    // Regenerate the token for security
                    $newToken = bin2hex(random_bytes(32));
                    $hashedToken = password_hash($newToken, PASSWORD_DEFAULT);
                    
                    // Store new token in database
                    $userModel->storeRememberToken($user->id, $hashedToken);
                    
                    // Update cookie with new token
                    setcookie('remember_user', $user->id . ':' . $newToken, time() + 60*60*24*30, '/');
                } else {
                    // Invalid token - clear the cookie
                    setcookie('remember_user', '', time() - 3600, '/');
                }
            }
        }
    }
    
    /**
     * Load a model
     * 
     * @param string $model The model name
     * @return object The model instance
     */
    private function loadModel($model) {
        // Require model file
        require_once APP_PATH . '/models/' . $model . '.php';
        
        // Instantiate model
        return new $model();
    }

    public function run() {
        try {
            $url = $this->parseUrl();

            // Look for controller
            if(isset($url[0]) && file_exists(APP_PATH . '/controllers/' . ucwords($url[0]) . 'Controller.php')) {
                $this->currentController = ucwords($url[0]) . 'Controller';
                unset($url[0]);
            }

            // Check if controller file exists (but don't require it again since we already loaded all controllers in index.php)
            $controllerFile = APP_PATH . '/controllers/' . $this->currentController . '.php';
            if (!file_exists($controllerFile)) {
                throw new Exception("Controller not found: " . $this->currentController);
            }
            
            // Ensure the controller class exists before instantiating
            if (!class_exists($this->currentController)) {
                throw new Exception("Controller class not found: " . $this->currentController);
            }
            
            // Instantiate the controller
            $this->currentController = new $this->currentController();

            // Check for method
            if(isset($url[1])) {
                if(method_exists($this->currentController, $url[1])) {
                    $this->currentMethod = $url[1];
                    unset($url[1]);
                } else {
                    throw new Exception("Method not found: " . $url[1]);
                }
            }

            // Get params
            $this->params = $url ? array_values($url) : [];

            // Call the method with parameters
            call_user_func_array([$this->currentController, $this->currentMethod], $this->params);
            
        } catch (Exception $e) {
            // Log the error
            error_log($e->getMessage());
            // Set environment variable for development
            define('ENVIRONMENT', 'development');
            // Show friendly error page
            include APP_PATH . '/views/pages/error.php';
        }
    }

    protected function parseUrl() {
        if(isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            return explode('/', $url);
        }
        return [];
    }
}