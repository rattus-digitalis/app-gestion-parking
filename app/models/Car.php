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

    // Récupère UNE voiture (1 utilisateur = 1 voiture)
    public function getByUserId(int $userId): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM cars WHERE user_id = ?");
        $stmt->execute([$userId]);
        $car = $stmt->fetch(PDO::FETCH_ASSOC);
        return $car ?: null;
    }
}
