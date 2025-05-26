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
        $password = password_hash(trim($postData['password']), PASSWORD_DEFAULT); // ✅ Hash

        $userModel = new User();

        if ($userModel->getUserByEmail($email)) {
            echo "❌ Cet email est déjà utilisé.";
            return;
        }

        $result = $userModel->createUser($lastName, $firstName, $email, $phone, $password);

        if ($result) {
            header('Location: /?page=login'); // mieux de rediriger vers login
            exit;
        } else {
            echo "❌ Erreur lors de l'enregistrement du compte.";
        }
    }

    public function login($postData)
    {
        if (empty($postData['email']) || empty($postData['password'])) {
            echo "❌ Email et mot de passe requis.";
            return;
        }

        $email = htmlspecialchars(trim($postData['email']));
        $password = trim($postData['password']);

        $userModel = new User();
        $user = $userModel->getUserByEmail($email);

        // Vérifie le mot de passe hashé
        if ($user && password_verify($password, $user['password'])) {
            // ✅ Connexion réussie
            $_SESSION['user'] = [
                'id' => $user['id'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'email' => $user['email'],
                'phone' => $user['phone'] ?? '',
                'role' => $user['role']
            ];

            $userModel->setStatus($user['id'], 'online');

            if ($user['role'] === 'admin') {
                header('Location: /?page=dashboard_admin');
            } else {
                header('Location: /?page=dashboard_user');
            }
            exit;
        } else {
            echo "❌ Identifiants incorrects.";
        }
    }

    public function updateCurrentUser(array $data)
    {
        if (!isset($_SESSION['user'])) {
            header("Location: /?page=login");
            exit;
        }

        $userModel = new User();
        $id = $_SESSION['user']['id'];

        $firstName = htmlspecialchars($data['first_name'] ?? '');
        $lastName = htmlspecialchars($data['last_name'] ?? '');
        $email = htmlspecialchars($data['email'] ?? '');
        $phone = htmlspecialchars($data['phone'] ?? '');
        $password = trim($data['password'] ?? '');

        $current = $userModel->getUserById($id);
        $role = $current['role'];
        $status = $current['status'];

        $passwordHashed = !empty($password)
            ? password_hash($password, PASSWORD_DEFAULT)
            : $current['password'];

        $userModel->updateUserWithPassword($id, $lastName, $firstName, $email, $phone, $role, $status, $passwordHashed);

        $_SESSION['user']['first_name'] = $firstName;
        $_SESSION['user']['last_name'] = $lastName;
        $_SESSION['user']['email'] = $email;
        $_SESSION['user']['phone'] = $phone;

        header("Location: /?page=mon_compte");
    }
}
