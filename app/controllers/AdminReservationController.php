<?php
require_once __DIR__ . '/../models/Reservation.php';

class AdminReservationController
{
    private $reservationModel;

    public function __construct()
    {
        $this->reservationModel = new Reservation();
    }

    public function listReservations()
    {
        $reservations = $this->reservationModel->getAllReservations();
        require __DIR__ . '/../views/admin/reservations_list.php';
    }

    public function editReservation()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id']);
            $data = [
                'user_id' => intval($_POST['user_id']),
                'parking_id' => intval($_POST['parking_id']),
                'date_start' => $_POST['date_start'],
                'date_end' => $_POST['date_end'],
                'status' => $_POST['status'],
            ];

            $this->reservationModel->updateReservation($id, $data);
            header('Location: /?page=reservations_list');
            exit;
        } elseif (isset($_GET['id'])) {
            $reservation = $this->reservationModel->getReservationById(intval($_GET['id']));
            require __DIR__ . '/../views/admin/edit_reservation.php';
        } else {
            header('Location: /?page=reservations_list');
            exit;
        }
    }

    public function deleteReservation()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_reservation_id'])) {
            $this->reservationModel->deleteReservation(intval($_POST['delete_reservation_id']));
        }
        header('Location: /?page=reservations_list');
        exit;
    }
}
