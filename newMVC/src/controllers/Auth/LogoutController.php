<?php

class LogoutController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    // Perform logout: clear session and redirect to login view
    public function logout(): void
    {
        // clear session data
        $_SESSION = [];
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }
        session_destroy();

        // redirect to login page
        redirectTo('index.php?action=login');
    }
}
