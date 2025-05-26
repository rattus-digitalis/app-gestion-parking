<?php

class Car
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = new PDO(
            'mysql:host=mysql;dbname=zenpark;charset=utf8',
            getenv('MYSQL_USER'),
            getenv('MYSQL_PASSWORD')
        );
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Récupère toutes les voitures d’un utilisateur
     */
    public function getByUserId(int $userId): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM cars WHERE user_id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Crée ou met à jour la voiture de l’utilisateur
     */
    public function save(int $userId, string $marque, string $modele, string $immat, string $couleur): bool
    {
        $stmt = $this->pdo->prepare("SELECT id FROM cars WHERE user_id = ?");
        $stmt->execute([$userId]);

        if ($stmt->fetchColumn()) {
            // Update
            $stmt = $this->pdo->prepare("
                UPDATE cars SET marque = ?, modele = ?, immatriculation = ?, couleur = ?
                WHERE user_id = ?
            ");
            return $stmt->execute([$marque, $modele, $immat, $couleur, $userId]);
        } else {
            // Insert
            $stmt = $this->pdo->prepare("
                INSERT INTO cars (user_id, marque, modele, immatriculation, couleur)
                VALUES (?, ?, ?, ?, ?)
            ");
            return $stmt->execute([$userId, $marque, $modele, $immat, $couleur]);
        }
    }
}
