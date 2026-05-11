<?php

require_once __DIR__ . '/../../lib/database.php';
require_once __DIR__ . '/../../model/user.php';
// use the LogoutController to centralize logout behaviour
require_once __DIR__ . '/LogoutController.php';

class LoginController
{
    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
    function connect(array $input)
    {
        if (!empty($input['email']) && !empty($input['password'])) {
            $email = $input['email'];
            $hasedpassword = gethashPassword($email);

            if ($hasedpassword && password_verify($input['password'], $hasedpassword)) {
                $pdo = \DatabaseConnection::getConnection();
                $userRepository = new \UserRepository($pdo);
                $info_user = $userRepository->authentication($email, $hasedpassword);
                if ($info_user) {
                    $info_user['DateConnexion'] = date("Y-m-d H:i:s");
                    $_SESSION['user'] = $info_user;
                    header('Location: index.php?action=user');
                    exit();
                } else {
                    $_SESSION['error'] = "Mot de passe ou email faux";
                    redirectTo('index.php?action=login');
                    exit();
                }
            } else {
                $_SESSION['error'] = "Mot de passe ou email faux";
                redirectTo('index.php?action=login');
                exit();
            }
        } else {
            $_SESSION['error'] = "Mot de passe ou email faux";
            redirectTo('index.php?action=login');
            exit();
        }
    }

    function checkconnection(string $DateConnexion)
    {
        $currentDate = new DateTime();
        $DateConnexion = new DateTime($DateConnexion);
        $interval = $currentDate->diff($DateConnexion);

        $TotalHours = ($interval->days * 24) + $interval->h;

        // Check if the interval is upper or lower than 12 hours
        if ($TotalHours >= 12) {
            $logoutController = new \LogoutController();
            $logoutController->logout();
        }
    }
}
