<?php
require_once __DIR__ . '/../../config/constants.php';

class Parking
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = new PDO(DB_DSN, DB_USER, DB_PASS);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // Récupère toutes les places de parking actives, triées par étage et numéro de place (tri numérique)
    public function getAllParkings()
    {
        $sql = "SELECT id, numero_place, etage, type_place, statut, disponible_depuis, date_maj, derniere_reservation_id, commentaire, actif
                FROM parking
                WHERE actif = 1
                ORDER BY etage, CAST(numero_place AS UNSIGNED)";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getByType(string $type): array
    {
        $sql = "SELECT * FROM parking
                WHERE actif = 1 AND type_place = ? AND statut = 'libre'
                ORDER BY etage, CAST(numero_place AS UNSIGNED)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$type]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Met à jour le statut d'une place et la date de mise à jour
    public function updateStatus(int $id, string $status)
    {
        $sql = "UPDATE parking SET statut = ?, date_maj = NOW() WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$status, $id]);
    }

    public function getById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM parking WHERE id = ?");
        $stmt->execute([$id]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result ?: null;
    }

    // Alias attendu par ReservationController
    public function getAll()
    {
        return $this->getAllParkings();
    }
}
