<?php
require_once __DIR__ . '/../models/User.php';

class AdminUserController
{
    public function listUsers()
    {
        $userModel = new User();
        $users = $userModel->getAllUsers();

        require_once __DIR__ . '/../views/admin/users_list.php';
    }

    public function handlePost($postData)
    {
        $userModel = new User();

        if (isset($postData['delete_user_id'])) {
            $userModel->deleteUserById($postData['delete_user_id']);
        }

        if (isset($postData['edit_user_id'])) {
            // Ici tu peux gérer la modification (à implémenter)
        }

        header('Location: /?page=admin_users');
        exit;
    }
    public function editUserForm($id)
{
    $userModel = new User();
    $user = $userModel->getUserById($id);

    if (!$user) {
        header('Location: /?page=admin_users');
        exit;
    }

    require_once __DIR__ . '/../views/admin/edit_user.php';
}

public function updateUser($postData)
{
    if (!isset($postData['id'])) {
        header('Location: /?page=admin_users');
        exit;
    }

    $id = $postData['id'];
    $lastName = htmlspecialchars($postData['last_name'] ?? '');
    $firstName = htmlspecialchars($postData['first_name'] ?? '');
    $email = htmlspecialchars($postData['email'] ?? '');
    $phone = htmlspecialchars($postData['phone'] ?? '');
    $role = htmlspecialchars($postData['role'] ?? 'user');
    $status = htmlspecialchars($postData['status'] ?? 'offline');

    $userModel = new User();
    $userModel->updateUser($id, $lastName, $firstName, $email, $phone, $role, $status);

    header('Location: /?page=admin_users');
    exit;
}




}
