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

    public function create(int $userId, int $parkingId, string $start, string $end, string $status = 'pending', ?int $carId = null): bool
    {
        $stmt = $this->pdo->prepare("
            INSERT INTO reservations (user_id, parking_id, date_start, date_end, status, car_id)
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        return $stmt->execute([$userId, $parkingId, $start, $end, $status, $carId]);
    }

    public function cancel(int $id): bool
    {
        $stmt = $this->pdo->prepare("UPDATE reservations SET status = 'cancelled', updated_at = NOW() WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getByUserId(int $userId): array
    {
        $stmt = $this->pdo->prepare("
            SELECT r.*, p.numero_place, p.etage
            FROM reservations r
            JOIN parking p ON r.parking_id = p.id
            WHERE r.user_id = ?
            ORDER BY r.date_start DESC
        ");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReservationById(int $id): ?array
    {
        $stmt = $this->pdo->prepare("
            SELECT r.*, p.numero_place, p.etage
            FROM reservations r
            JOIN parking p ON r.parking_id = p.id
            WHERE r.id = ?
        ");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function updateReservation(int $id, array $data): bool
    {
        $stmt = $this->pdo->prepare("
            UPDATE reservations
            SET user_id = ?, parking_id = ?, date_start = ?, date_end = ?, status = ?, car_id = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['user_id'],
            $data['parking_id'],
            $data['date_start'],
            $data['date_end'],
            $data['status'],
            $data['car_id'],
            $id
        ]);
    }

    public function isAvailable(int $parkingId, string $start, string $end, ?int $excludeReservationId = null): bool
    {
        $sql = "
            SELECT COUNT(*) FROM reservations
            WHERE parking_id = ?
            AND status != 'cancelled'
            AND (
                (date_start < ? AND date_end > ?) OR
                (date_start >= ? AND date_start < ?)
            )
        ";

        $params = [$parkingId, $end, $start, $start, $end];

        if ($excludeReservationId !== null) {
            $sql .= " AND id != ?";
            $params[] = $excludeReservationId;
        }

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchColumn() == 0;
    }
}
