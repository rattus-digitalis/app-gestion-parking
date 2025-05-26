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
            if ($r['status'] === 'cancelled' || $r['date_end'] <= $now) {
                $past[] = $r;
            } else {
                $actives[] = $r;
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

        if (!$reservationModel->isAvailable((int)$parkingId, $start, $end)) {
            echo "❌ Ce créneau n'est pas disponible.";
            return;
        }

        $reservationModel->create($userId, (int)$parkingId, $start, $end, 'pending', (int)$carId);

        header('Location: /?page=mes_reservations');
        exit;
    }

    public function editForm(int $reservationId)
    {
        $reservationModel = new Reservation();
        $reservation = $reservationModel->getReservationById($reservationId);

        if (!$reservation || $reservation['user_id'] != $_SESSION['user']['id']) {
            http_response_code(403);
            echo "Accès refusé.";
            exit;
        }

        $parkingModel = new Parking();
        $parkings = $parkingModel->getAllParkings();

        require __DIR__ . '/../views/pages/edit_reservation.php';
    }

    public function update(array $data)
    {
        $reservationModel = new Reservation();

        $reservationId = (int)$data['id'];
        $userId = $_SESSION['user']['id'];
        $parkingId = (int)$data['parking_id'];
        $start = $data['start_time'];
        $end = $data['end_time'];

        if (!$reservationModel->isAvailable($parkingId, $start, $end, $reservationId)) {
            echo "❌ La place n'est pas disponible à ce créneau.";
            exit;
        }

        $reservationModel->updateReservation($reservationId, [
            'user_id' => $userId,
            'parking_id' => $parkingId,
            'date_start' => $start,
            'date_end' => $end,
            'status' => 'pending',
            'car_id' => null
        ]);

        header('Location: /?page=mes_reservations');
        exit;
    }
}
