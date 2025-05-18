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

public function setStatus($userId, $status)
{
    $stmt = $this->pdo->prepare("UPDATE users SET status = ? WHERE id = ?");
    return $stmt->execute([$status, $userId]);
}


}

