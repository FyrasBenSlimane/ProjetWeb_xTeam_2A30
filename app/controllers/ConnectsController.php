<?php

/**
 * Lightweight redirect controller - forwards requests to UserController
 * This maintains URL compatibility with existing links
 */
class ConnectsController extends Controller
{
    public function __construct()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            redirect('users/auth?action=login');
        }

        // Check if user is a freelancer
        if ($_SESSION['user_account_type'] != 'freelancer') {
            redirect('');
        }
    }

    public function index()
    {
        // Redirect to the connects method in UserController
        $user = new UserController();
        $user->connects();
    }
}
