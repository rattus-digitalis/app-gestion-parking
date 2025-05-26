<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/../models/Reservation.php';
require_once __DIR__ . '/../models/Parking.php';
require_once __DIR__ . '/../models/Car.php';

class ReservationController
{
    public function mesReservations()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /?page=login');
            exit;
        }

        $reservationModel = new Reservation();
        $all = $reservationModel->getByUserId($_SESSION['user']['id']);

        $now = date('Y-m-d H:i:s');
        $actives = [];
        $past = [];

        foreach ($all as $r) {
            if ($r['date_end'] > $now) {
                $actives[] = $r;
            } else {
                $past[] = $r;
            }
        }

        require __DIR__ . '/../views/pages/mes_reservations.php';
    }

    public function form()
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /?page=login');
            exit;
        }

        $parkingModel = new Parking();
        $carModel = new Car();

        $types = ['standard', 'chargeur', 'moto', 'handicap'];

        $parkingsByType = [];
        foreach ($types as $type) {
            $parkingsByType[$type] = $parkingModel->getByType($type);
        }

        $car = $carModel->getByUserId($_SESSION['user']['id']);

        require __DIR__ . '/../views/pages/nouvelle_reservation.php';
    }

    public function cancelReservation()
{
    if (!isset($_SESSION['user'])) {
        header('Location: /?page=login');
        exit;
    }

    $id = $_GET['id'] ?? null;
    if (!$id) {
        http_response_code(400);
        echo "ID de réservation manquant.";
        return;
    }

    $reservationModel = new Reservation();
    $reservationModel->cancel((int)$id);

    header('Location: /?page=mes_reservations');
    exit;
}

    public function create(array $data)
    {
        if (!isset($_SESSION['user'])) {
            header('Location: /?page=login');
            exit;
        }

        $userId    = $_SESSION['user']['id'];
        $parkingId = $data['parking_id'] ?? null;
        $carId     = $data['car_id'] ?? null;
        $start     = $data['start_time'] ?? null;
        $end       = $data['end_time'] ?? null;

        if (!$parkingId || !$carId || !$start || !$end) {
            echo "❌ Tous les champs sont obligatoires.";
            return;
        }

        $reservationModel = new Reservation();
        $reservationModel->create($userId, (int)$parkingId, $start, $end, 'pending', (int)$carId);

        header('Location: /?page=mes_reservations');
        exit;
    }
}
