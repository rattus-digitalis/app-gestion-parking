<?php

class User
{
    private $pdo;

    public function __construct()
    {
        // Connexion PDO
        $this->pdo = new PDO(
            'mysql:host=mysql;dbname=zenpark;charset=utf8',
            $_ENV['MYSQL_USER'],
            $_ENV['MYSQL_PASSWORD']
        );
    }

    public function createUser($lastName, $firstName, $email, $phone, $password)
{
    $stmt = $this->pdo->prepare("
        INSERT INTO users (last_name, first_name, email, phone, password)
        VALUES (?, ?, ?, ?, ?)
    ");
    return $stmt->execute([$lastName, $firstName, $email, $phone, $password]);
}
public function getUserByEmail($email)
{
    $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function setStatus($id, $status)
{
    $stmt = $this->pdo->prepare("UPDATE users SET status = ? WHERE id = ?");
    return $stmt->execute([$status, $id]);
}

public function getAllUsers()
{
    $stmt = $this->pdo->query("SELECT id, last_name, first_name, email, phone, role, status FROM users");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function deleteUserById($id)
{
    $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = ?");
    return $stmt->execute([$id]);
}

public function getUserById($id)
{
    $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

public function updateUser($id, $lastName, $firstName, $email, $phone, $role, $status)
{
    $stmt = $this->pdo->prepare("
        UPDATE users SET last_name = ?, first_name = ?, email = ?, phone = ?, role = ?, status = ? WHERE id = ?
    ");
    return $stmt->execute([$lastName, $firstName, $email, $phone, $role, $status, $id]);
}




}

