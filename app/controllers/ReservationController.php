<?php
require_once __DIR__ . '/../models/Reservation.php';
require_once __DIR__ . '/../models/Parking.php';

class ReservationController
{
    public function form()
    {
        $parkingModel = new Parking();
        $parkings = $parkingModel->getAll();
        require __DIR__ . '/../views/pages/nouvelle_reservation.php';
    }

    public function create(array $data)
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /?page=login');
            exit;
        }

        $userId = $_SESSION['user']['id'];
        $parkingId = $data['parking_id'] ?? null;
        $start = $data['start_time'] ?? null;
        $end = $data['end_time'] ?? null;

        if (!$parkingId || !$start || !$end) {
            echo "❌ Tous les champs sont obligatoires.";
            return;
        }

        $reservationModel = new Reservation();
        $reservationModel->create($userId, $parkingId, $start, $end);

        header('Location: /?page=dashboard_user');
    }
}
