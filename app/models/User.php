<?php

class User
{
    private PDO $pdo;

    public function __construct()
    {
        try {
            // Connexion à MySQL (service "mysql" dans Docker)
            $this->pdo = new PDO(
                'mysql:host=mysql;dbname=parkly;charset=utf8',
                getenv('MYSQL_USER'),      // ✔️ plus sûr que $_ENV[]
                getenv('MYSQL_PASSWORD')
            );
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            die('Erreur de connexion à la base de données : ' . $e->getMessage());
        }
    }

    /**
     * Crée un nouvel utilisateur
     */
    public function createUser(
        string $lastName,
        string $firstName,
        string $email,
        string $phone,
        string $password
    ): bool {
        $stmt = $this->pdo->prepare("
            INSERT INTO users (last_name, first_name, email, phone, password)
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$lastName, $firstName, $email, $phone, $password]);
    }

    /**
     * Récupère un utilisateur par son email
     */
    public function getUserByEmail(string $email): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    /**
     * Récupère un utilisateur par ID
     */
    public function getUserById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user ?: null;
    }

    /**
     * Met à jour le statut (online/offline) d’un utilisateur
     */
    public function setStatus(int $id, string $status): bool
    {
        $stmt = $this->pdo->prepare("UPDATE users SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    /**
     * Récupère tous les utilisateurs (admin)
     */
    public function getAllUsers(): array
    {
        $stmt = $this->pdo->query("SELECT id, last_name, first_name, email, phone, role, status FROM users");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Supprime un utilisateur par son ID
     */
    public function deleteUserById(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Met à jour un utilisateur (admin)
     */
    public function updateUser(
        int $id,
        string $lastName,
        string $firstName,
        string $email,
        string $phone,
        string $role,
        string $status
    ): bool {
        $stmt = $this->pdo->prepare("
            UPDATE users 
            SET last_name = ?, first_name = ?, email = ?, phone = ?, role = ?, status = ?
            WHERE id = ?
        ");
        return $stmt->execute([$lastName, $firstName, $email, $phone, $role, $status, $id]);
    }

    /**
     * Met à jour un utilisateur (avec mot de passe)
     */
    public function updateUserWithPassword(
        int $id,
        string $lastName,
        string $firstName,
        string $email,
        string $phone,
        string $role,
        string $status,
        string $password
    ): bool {
        $stmt = $this->pdo->prepare("
            UPDATE users SET 
                last_name = ?, 
                first_name = ?, 
                email = ?, 
                phone = ?, 
                role = ?, 
                status = ?, 
                password = ?
            WHERE id = ?
        ");
        return $stmt->execute([$lastName, $firstName, $email, $phone, $role, $status, $password, $id]);
    }
}
