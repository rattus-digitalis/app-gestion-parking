<?php
require_once __DIR__ . '/../models/Reservation.php';

class AdminReservationController
{
    private Reservation $reservationModel;

    public function __construct()
    {
        $this->reservationModel = new Reservation();
    }

    /**
     * Affiche la liste complète des réservations
     */
    public function listReservations(): void
    {
        $reservations = $this->reservationModel->getAll();
        require __DIR__ . '/../views/admin/reservations_list.php';
    }

    /**
     * Affiche ou traite le formulaire d'édition d'une réservation
     */
    public function editReservation(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = intval($_POST['id']);
            $data = [
                'user_id'    => intval($_POST['user_id']),
                'parking_id' => intval($_POST['parking_id']),
                'date_start' => $_POST['date_start'],
                'date_end'   => $_POST['date_end'],
                'status'     => $_POST['status'],
                'car_id'     => isset($_POST['car_id']) ? intval($_POST['car_id']) : null,
            ];

            $this->reservationModel->updateReservation($id, $data);
            header('Location: /?page=reservations_list');
            exit;
        }

        if (isset($_GET['id'])) {
            $reservation = $this->reservationModel->getReservationById(intval($_GET['id']));
            if (!$reservation) {
                http_response_code(404);
                echo "Réservation introuvable.";
                exit;
            }
            require __DIR__ . '/../views/admin/edit_reservation.php';
        } else {
            header('Location: /?page=reservations_list');
            exit;
        }
    }

    /**
     * Annule une réservation (soft delete en changeant son statut)
     */
    public function deleteReservation(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_reservation_id'])) {
            $this->reservationModel->cancel(intval($_POST['delete_reservation_id']));
        }

        header('Location: /?page=reservations_list');
        exit;
    }
}
