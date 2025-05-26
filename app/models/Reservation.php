<?php

require_once __DIR__ . '/../../config/constants.php';
require_once __DIR__ . '/Tarif.php';

class Reservation
{
    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = new PDO(DB_DSN, DB_USER, DB_PASS);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    public function create(
        int $userId,
        int $parkingId,
        string $start,
        string $end,
        string $status = 'pending',
        ?int $carId = null
    ): bool {
        $sql = "
            INSERT INTO reservations (user_id, parking_id, date_start, date_end, status, car_id)
            VALUES (?, ?, ?, ?, ?, ?)
        ";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$userId, $parkingId, $start, $end, $status, $carId]);
    }

    public function cancel(int $id): bool
    {
        $sql = "UPDATE reservations SET status = 'cancelled', updated_at = NOW() WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function getByUserId(int $userId): array
    {
        $sql = "
            SELECT r.*, p.numero_place, p.etage
            FROM reservations AS r
            JOIN parking AS p ON r.parking_id = p.id
            WHERE r.user_id = ?
            ORDER BY r.date_start DESC
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getReservationById(int $id): ?array
    {
        $sql = "
            SELECT r.*, p.numero_place, p.etage
            FROM reservations AS r
            JOIN parking AS p ON r.parking_id = p.id
            WHERE r.id = ?
        ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);
        $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

        return $reservation ?: null;
    }

    public function updateReservation(int $id, array $data): bool
    {
        $sql = "
            UPDATE reservations
            SET user_id = ?, parking_id = ?, date_start = ?, date_end = ?, status = ?, car_id = ?
            WHERE id = ?
        ";
        $stmt = $this->pdo->prepare($sql);

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

        return (int) $stmt->fetchColumn() === 0;
    }

    public function calculerPrix(string $start, string $end, string $type): float
    {
        $tarifModel = new Tarif();
        $tarifs = $tarifModel->getAll();

        $prixHeure = isset($tarifs[$type]['heure']) ? (float) $tarifs[$type]['heure'] : 0.0;

        $debut = new DateTimeImmutable($start);
        $fin = new DateTimeImmutable($end);

        $diffHeures = ($fin->getTimestamp() - $debut->getTimestamp()) / 3600;

        return round($prixHeure * $diffHeures, 2);
    }

    public function marquerCommePayee(int $id): bool
    {
        $sql = "UPDATE reservations SET paid = 1 WHERE id = ?";
        $stmt = $this->pdo->prepare($sql);
        return $stmt->execute([$id]);
    }

    public function getAll(): array
    {
        $sql = "
            SELECT r.*,
                   u.first_name, u.last_name,
                   p.numero_place, p.etage,
                   c.marque, c.modele, c.immatriculation
            FROM reservations AS r
            JOIN users AS u ON r.user_id = u.id
            JOIN parking AS p ON r.parking_id = p.id
            LEFT JOIN cars AS c ON r.car_id = c.id
            WHERE r.status != 'cancelled'
            ORDER BY r.date_start DESC
        ";
        $stmt = $this->pdo->query($sql);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
