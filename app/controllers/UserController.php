<?php

require_once __DIR__ . '/../models/User.php';

class UserController
{
    public function register($postData)
    {
        if (
            empty($postData['last_name']) ||
            empty($postData['first_name']) ||
            empty($postData['email']) ||
            empty($postData['phone']) ||
            empty($postData['password'])
        ) {
            echo "❌ Tous les champs sont obligatoires.";
            return;
        }

        $lastName = htmlspecialchars(trim($postData['last_name']));
        $firstName = htmlspecialchars(trim($postData['first_name']));
        $email = htmlspecialchars(trim($postData['email']));
        $phone = htmlspecialchars(trim($postData['phone']));
        $password = trim($postData['password']); // non hashé pour le moment

        $userModel = new User();

        // Vérifie si l'email existe déjà
        if ($userModel->getUserByEmail($email)) {
            echo "❌ Cet email est déjà utilisé.";
            return;
        }

        $result = $userModel->createUser($lastName, $firstName, $email, $phone, $password);

        if ($result) {
            header('Location: /?page=dashboard');
            exit;
        } else {
            echo "❌ Erreur lors de l'enregistrement du compte.";
        }
    }

    public function login($postData)
    {
        session_start();

        if (empty($postData['email']) || empty($postData['password'])) {
            echo "❌ Email et mot de passe requis.";
            return;
        }

        $email = htmlspecialchars(trim($postData['email']));
        $password = trim($postData['password']);

        $userModel = new User();
        $user = $userModel->getUserByEmail($email);

        if ($user && $password === $user['password']) {
            // ✅ Connexion réussie
            $_SESSION['user'] = [
                'id' => $user['id'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'email' => $user['email'],
                'role' => $user['role']
            ];

            $userModel->setStatus($user['id'], 'online');

            header('Location: /?page=dashboard');
            exit;
        } else {
            echo "❌ Identifiants incorrects.";
        }
    }
}
