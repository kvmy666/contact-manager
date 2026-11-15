<?php
// Handles admin login and logout.

require_once __DIR__ . '/../models/Admin.php';

class AuthController
{
    public function login()
    {
        if (is_logged_in()) {
            redirect('index.php');
        }

        $error = '';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf();

            $username = post('username');
            $password = post('password', false);

            $admin = (new Admin())->findByUsername($username);

            if ($admin && password_verify($password, $admin['password_hash'])) {
                session_regenerate_id(true);
                unset($_SESSION['csrf_token']);
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_username'] = $admin['username'];
                $_SESSION['last_active'] = time();

                flash('Welcome back.');
                redirect('index.php');
            }

            $error = 'Invalid username or password.';
        }

        require __DIR__ . '/../views/auth/login.php';
    }

    public function logout()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('index.php');
        }

        verify_csrf();

        $_SESSION = [];
        session_destroy();

        // A fresh session is needed to store the goodbye message.
        session_start();
        flash('You have been logged out.');

        redirect('index.php');
    }
}
