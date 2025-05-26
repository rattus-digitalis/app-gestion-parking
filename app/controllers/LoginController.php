<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../models/User.php';

class LoginController
{
    public function login(array $data)
    {
        $email = trim($data['email'] ?? '');
        $password = trim($data['password'] ?? '');

        if (empty($email) || empty($password)) {
            echo "❌ Veuillez remplir tous les champs.";
            return;
        }

        $userModel = new User();
        $user = $userModel->getUserByEmail($email);

        if (!$user) {
            echo "❌ Utilisateur introuvable.";
            return;
        }

        // ✅ Vérification du mot de passe hashé
        if (!password_verify($password, $user['password'])) {
            echo "❌ Mot de passe incorrect.";
            return;
        }

        $_SESSION['user'] = [
            'id'         => $user['id'],
            'email'      => $user['email'],
            'role'       => $user['role'],
            'first_name' => $user['first_name'] ?? '',
            'last_name'  => $user['last_name'] ?? '',
            'phone'      => $user['phone'] ?? '',
        ];

        $userModel->setStatus($user['id'], 'online');

        header('Location: /?page=dashboard');
        exit;
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
