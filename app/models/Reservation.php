<?php
require_once __DIR__ . '/../../config/constants.php';

class Reservation
{
    private $pdo;

    public function __construct()
    {
        $this->pdo = new PDO(DB_DSN, DB_USER, DB_PASS);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // Récupérer toutes les réservations avec infos utilisateur et place
    public function getAllReservations()
    {
        $sql = "SELECT r.id, u.first_name, u.last_name, p.numero_place, r.date_start, r.date_end, r.status
                FROM reservations r
                JOIN users u ON r.user_id = u.id
                JOIN parking p ON r.parking_id = p.id
                ORDER BY r.date_start DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupérer une réservation par son ID
    public function getReservationById(int $id)
    {
        $stmt = $this->pdo->prepare("
            SELECT r.*, u.first_name, u.last_name, p.numero_place 
            FROM reservations r
            JOIN users u ON r.user_id = u.id
            JOIN parking p ON r.parking_id = p.id
            WHERE r.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Créer une réservation
    public function createReservation($user_id, $parking_id, $date_start, $date_end, $status = 'pending')
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO reservations (user_id, parking_id, date_start, date_end, status)
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$user_id, $parking_id, $date_start, $date_end, $status]);
    }

    // Mettre à jour une réservation
    public function updateReservation(int $id, $data)
    {
        $stmt = $this->pdo->prepare("
            UPDATE reservations
            SET user_id = ?, parking_id = ?, date_start = ?, date_end = ?, status = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['user_id'], 
            $data['parking_id'], 
            $data['date_start'], 
            $data['date_end'], 
            $data['status'], 
            $id
        ]);
    }

    // Supprimer une réservation
    public function deleteReservation(int $id)
    {
        $stmt = $this->pdo->prepare("DELETE FROM reservations WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
