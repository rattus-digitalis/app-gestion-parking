<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../models/User.php';

class LoginController
{
    public function login(array $data)
    {
        $email = $data['email'] ?? '';
        $password = $data['password'] ?? '';

        if (!$email || !$password) {
            echo "Veuillez remplir tous les champs.";
            return;
        }

        $userModel = new User();
        $user = $userModel->getUserByEmail($email);

        if ($user /* && password_verify($password, $user['password']) */ && $password === $user['password']) {
            // ⚠️ REMARQUE : remplace "$password === $user['password']" par password_verify() dès que tu hashes les mots de passe

            $_SESSION['user'] = [
                'id'         => $user['id'],
                'email'      => $user['email'],
                'role'       => $user['role'],
                'first_name' => $user['first_name'] ?? '',
                'last_name'  => $user['last_name'] ?? '',
            ];

            header('Location: /?page=dashboard');
            exit;
        } else {
            echo "Identifiants incorrects.";
        }
    }

    public function logout()
    {
        if (isset($_SESSION['user'])) {
            $userModel = new User();
            $userModel->setStatus($_SESSION['user']['id'], 'offline');
        }

        session_unset();
        session_destroy();
        header('Location: /?page=login');
        exit;
    }
}
