<?php
require_once __DIR__ . '/../../config/constants.php';

class Reservation
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = new PDO(DB_DSN, DB_USER, DB_PASS);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Créer une réservation
     */
    public function create(int $userId, int $parkingId, string $start, string $end, string $status = 'pending'): bool
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO reservations (user_id, parking_id, date_start, date_end, status)
            VALUES (?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$userId, $parkingId, $start, $end, $status]);
    }

    /**
     * Récupère toutes les réservations (admin)
     */
    public function getAllReservations(): array
    {
        $sql = "SELECT r.id, u.first_name, u.last_name, p.numero_place, r.date_start, r.date_end, r.status
                FROM reservations r
                JOIN users u ON r.user_id = u.id
                JOIN parking p ON r.parking_id = p.id
                ORDER BY r.date_start DESC";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Récupère une réservation par ID
     */
    public function getReservationById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT r.*, u.first_name, u.last_name, p.numero_place 
            FROM reservations r
            JOIN users u ON r.user_id = u.id
            JOIN parking p ON r.parking_id = p.id
            WHERE r.id = ?
        ");
        $stmt->execute([$id]);
        $res = $stmt->fetch(PDO::FETCH_ASSOC);
        return $res ?: null;
    }

    /**
     * Met à jour une réservation
     */
    public function updateReservation(int $id, array $data): bool
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

    /**
     * Supprime une réservation
     */
    public function deleteReservation(int $id): bool
    {
        $stmt = $this->pdo->prepare("DELETE FROM reservations WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
