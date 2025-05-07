<?php
class Application {
    protected $currentController = 'PagesController';
    protected $currentMethod = 'index';
    protected $params = [];

    public function __construct() {
        $this->handleRememberMeCookie();
    }

    private function handleRememberMeCookie() {
        if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_user'])) {
            list($userId, $token) = explode(':', $_COOKIE['remember_user']);
            
            if ($userId && $token) {
                $userModel = $this->loadModel('User');
                
                $user = $userModel->verifyRememberToken($userId, $token);
                
                if ($user) {
                    $_SESSION['user_id'] = $user->id;
                    $_SESSION['user_email'] = $user->email;
                    $_SESSION['user_name'] = $user->name;
                    $_SESSION['user_account_type'] = $user->account_type;
                    
                    $newToken = bin2hex(random_bytes(32));
                    $hashedToken = password_hash($newToken, PASSWORD_DEFAULT);
                    
                    $userModel->storeRememberToken($user->id, $hashedToken);
                    
                    setcookie('remember_user', $user->id . ':' . $newToken, time() + 60*60*24*30, '/');
                } else {
                    setcookie('remember_user', '', time() - 3600, '/');
                }
            }
        }
    }
    
    private function loadModel($model) {
        require_once APP_PATH . '/models/' . $model . '.php';
        
        return new $model();
    }

    public function run() {
        try {
            $url = $this->parseUrl();

            if(isset($url[0]) && file_exists(APP_PATH . '/controllers/' . ucwords($url[0]) . 'Controller.php')) {
                $this->currentController = ucwords($url[0]) . 'Controller';
                unset($url[0]);
            }

            $controllerFile = APP_PATH . '/controllers/' . $this->currentController . '.php';
            if (!file_exists($controllerFile)) {
                throw new Exception("Controller not found: " . $this->currentController);
            }

            if (!class_exists($this->currentController)) {
                 require_once $controllerFile;
                 if (!class_exists($this->currentController)) {
                    throw new Exception("Controller class not found: " . $this->currentController);
                 }
            }

            $this->currentController = new $this->currentController();

            if(isset($url[1])) {
                $methodName = preg_replace('/[^a-zA-Z0-9_]/', '', $url[1]);
                if(method_exists($this->currentController, $methodName)) {
                    $this->currentMethod = $methodName;
                    unset($url[1]);
                } else {
                    if (get_class($this->currentController) === 'DashboardController') {
                         throw new Exception("Method not found in DashboardController: " . $url[1]);
                    } else if (!method_exists($this->currentController, 'index')) {
                         throw new Exception("Method not found: " . $url[1] . " and no index method exists.");
                    }
                }
            }

            $this->params = $url ? array_values($url) : [];

            call_user_func_array([$this->currentController, $this->currentMethod], $this->params);

        } catch (Exception $e) {
            error_log($e->getMessage());
            define('ENVIRONMENT', 'development');
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
