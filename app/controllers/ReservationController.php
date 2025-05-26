<?php
require_once __DIR__ . '/../models/Reservation.php';
require_once __DIR__ . '/../models/Parking.php';
require_once __DIR__ . '/../models/Car.php';

class ReservationController
{
    public function form()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /?page=login');
            exit;
        }

        $parkingModel = new Parking();
        $carModel = new Car();

        // Filtrage des places par type
        $types = ['standard', 'chargeur', 'moto', 'handicap'];
        $parkingsByType = [];

        foreach ($types as $type) {
            if (method_exists($parkingModel, 'getByType')) {
                $parkingsByType[$type] = $parkingModel->getByType($type);
            } else {
                $parkingsByType[$type] = []; // fallback si méthode absente
            }
        }

        $car = $carModel->getByUserId($_SESSION['user']['id']);

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
        $carId = $data['car_id'] ?? null;
        $start = $data['start_time'] ?? null;
        $end = $data['end_time'] ?? null;

        if (!$parkingId || !$carId || !$start || !$end) {
            echo "❌ Tous les champs sont obligatoires.";
            return;
        }

        $reservationModel = new Reservation();
        $reservationModel->create($userId, (int)$parkingId, $start, $end);

        header('Location: /?page=dashboard_user');
        exit;
    }
}
