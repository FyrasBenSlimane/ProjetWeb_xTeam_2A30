<?php

/**
 * Base Controller
 * Loads models and views
 */
class Controller
{
    // Load model
    public function model($model)
    {
        // Clean up the model path and extract the class name
        $modelFile = $model;

        // If the model is in a subdirectory, get just the class name
        if (strpos($model, '/') !== false) {
            $parts = explode('/', $model);
            $model = end($parts);
        }

        // Include the file
        require_once APP_PATH . '/models/' . $modelFile . '.php';

        // Create a new instance using just the class name
        return new $model();
    }

    // Load view
    public function view($view, $data = [])
    {
        if (file_exists(APP_PATH . '/views/' . $view . '.php')) {
            require_once APP_PATH . '/views/' . $view . '.php';
        } else {
            throw new Exception('View does not exist: ' . $view);
        }
    }

    // Load helper
    public function helper($helper, $params = null)
    {
        // Clean up the helper path and extract the class name
        $helperFile = $helper;

        // If the helper is in a subdirectory, get just the class name
        if (strpos($helper, '/') !== false) {
            $parts = explode('/', $helper);
            $helper = end($parts);
        }

        // Include the file
        require_once APP_PATH . '/helpers/' . $helperFile . '.php';

        // Create a new instance using just the class name, passing any parameters
        return new $helper($params);
    }

    // Load component
    protected function component($name)
    {
        require_once APP_PATH . '/views/components/' . $name . '.php';
    }

    // JSON response
    protected function jsonResponse($data, $status = 200)
    {
        header('Content-Type: application/json');
        http_response_code($status);
        echo json_encode($data);
        exit;
    }
}
