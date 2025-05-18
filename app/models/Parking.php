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

    // Récupère toutes les places de parking actives, triées par étage et numéro de place
    public function getAllParkings()
    {
        $stmt = $this->pdo->query("SELECT id, numero_place, etage, type_place, statut, disponible_depuis, date_maj, derniere_reservation_id, commentaire, actif FROM parking WHERE actif = 1 ORDER BY etage, numero_place");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Met à jour le statut d'une place et la date de mise à jour
    public function updateStatus(int $id, string $status)
    {
        $stmt = $this->pdo->prepare("UPDATE parking SET statut = ?, date_maj = NOW() WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }
}
