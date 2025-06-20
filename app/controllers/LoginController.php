<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../models/User.php';

class LoginController
{
    public function login(array $data)
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        try {
            // Debug temporaire
            error_log("POST login data: " . print_r($data, true));

            $email = trim($data['email'] ?? '');
            $password = trim($data['password'] ?? '');

            if (empty($email) || empty($password)) {
                echo "❌ Veuillez remplir tous les champs.";
                return;
            }

            $userModel = new User();
            $user = $userModel->getUserByEmail($email); // Ensure this method exists in your User model

            if (!$user) {
                echo "❌ Utilisateur introuvable.";
                return;
            }

            // Vérification du mot de passe hashé
            if (!password_verify($password, $user['password'])) {
                echo "❌ Mot de passe incorrect.";
                return;
            }

            $_SESSION['user'] = [
                'id' => $user['id'],
                'email' => $user['email'],
                'role' => $user['role'],
                'first_name' => $user['first_name'] ?? '',
                'last_name' => $user['last_name'] ?? '',
                'phone' => $user['phone'] ?? '',
            ];

            $userModel->setStatus($user['id'], 'online');

            header('Location: /?page=dashboard');
            exit;

        } catch (Throwable $e) {
            error_log("💥 ERREUR dans login(): " . $e->getMessage());
            error_log($e->getTraceAsString());
            http_response_code(500);
            echo "Erreur serveur : " . $e->getMessage(); // For display in the browser
        }
    }

    public function logout()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

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
