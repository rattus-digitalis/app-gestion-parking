<?php

class User
{
    private PDO $pdo;

    public function __construct()
    {
        try {
            $this->pdo = new PDO(
                'mysql:host=mysql;port=3306;dbname=parkly;charset=utf8',
                'rattus',
                'rattus'
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('Erreur de connexion : ' . $e->getMessage());
        }
    }

    public function getAllUsers(): array
    {
        $stmt = $this->pdo->query("SELECT * FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public function getUserById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    public function createUser(string $lastName, string $firstName, string $email, string $phone, string $password, string $role = 'user'): bool
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO users (last_name, first_name, email, phone, password, role, status)
            VALUES (:last_name, :first_name, :email, :phone, :password, :role, 'offline')
        ");
        return $stmt->execute([
            'last_name' => $lastName,
            'first_name' => $firstName,
            'email' => $email,
            'phone' => $phone,
            'password' => $password,
            'role' => $role
        ]);
    }

    public function updateUser(int $id, string $lastName, string $firstName, string $email, string $phone, string $role, string $status): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE users
            SET last_name = :last_name, first_name = :first_name, email = :email, phone = :phone, role = :role, status = :status
            WHERE id = :id
        ");
        return $stmt->execute([
            'id' => $id,
            'last_name' => $lastName,
            'first_name' => $firstName,
            'email' => $email,
            'phone' => $phone,
            'role' => $role,
            'status' => $status
        ]);
    }

    public function deleteUserById(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    public function setStatus(int $id, string $status): bool
    {
        $stmt = $this->pdo->prepare("UPDATE users SET status = :status WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'status' => $status
        ]);
    }
}