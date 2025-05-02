<?php

/**
 * Lightweight redirect controller - forwards requests to UserController
 * This maintains URL compatibility with existing links
 */
class AppsController extends Controller
{
    public function __construct()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            redirect('users/auth?action=login');
        }
    }

    public function index()
    {
        // Redirect to the apps method in UserController
        $user = new UserController();
        $user->apps();
    }
}
