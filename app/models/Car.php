<?php
require_once __DIR__ . '/../../config/constants.php';

class Car
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = new PDO(DB_DSN, DB_USER, DB_PASS);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function getByUserId(int $userId): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM cars WHERE user_id = ?");
        $stmt->execute([$userId]);
        $car = $stmt->fetch(PDO::FETCH_ASSOC);
        return $car ?: null;
    }

    public function getById(int $id): ?array
{
    $stmt = $this->pdo->prepare("SELECT * FROM cars WHERE id = ?");
    $stmt->execute([$id]);
    $car = $stmt->fetch(PDO::FETCH_ASSOC);
    return $car ?: null;
}


    public function save(int $userId, string $marque, string $modele, string $immat, string $couleur, string $type): bool
    {
        $stmt = $this->pdo->prepare("SELECT id FROM cars WHERE user_id = ?");
        $stmt->execute([$userId]);
        $exists = $stmt->fetchColumn();

        if ($exists) {
            // UPDATE
            $stmt = $this->pdo->prepare("
                UPDATE cars SET marque = ?, modele = ?, immatriculation = ?, couleur = ?, type = ?
                WHERE user_id = ?
            ");
            return $stmt->execute([$marque, $modele, $immat, $couleur, $type, $userId]);
        } else {
            // INSERT
            $stmt = $this->pdo->prepare("
                INSERT INTO cars (user_id, marque, modele, immatriculation, couleur, type)
                VALUES (?, ?, ?, ?, ?, ?)
            ");
            return $stmt->execute([$userId, $marque, $modele, $immat, $couleur, $type]);
        }
    }
}
