<?php
require_once __DIR__ . '/../models/Reservation.php';
require_once __DIR__ . '/../models/Parking.php';
require_once __DIR__ . '/../models/Car.php'; // Ajout du modèle Car

class ReservationController
{
    public function form()
    {
        $parkingModel = new Parking();
        $carModel = new Car();

        $parkings = $parkingModel->getAllParkings(); // appel correct de la méthode
        $cars = $carModel->getByUserId($_SESSION['user']['id']);

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
        $reservationModel->create($userId, $parkingId, $start, $end, 'pending', $carId); // ajout du car_id

        header('Location: /?page=dashboard_user');
        exit;
    }
}
