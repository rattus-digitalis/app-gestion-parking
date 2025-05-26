<?php
require_once __DIR__ . '/../models/Reservation.php';

class PaiementController
{
    public function payer(int $reservationId)
    {
        $reservationModel = new Reservation();
        $reservation = $reservationModel->getReservationById($reservationId);

        if (!$reservation || $reservation['user_id'] != $_SESSION['user']['id']) {
            http_response_code(403);
            echo "Accès refusé.";
            return;
        }

        require __DIR__ . '/../views/pages/paiement.php';
    }

    public function effectuerPaiement(array $data)
    {
        $id = (int)$data['id'];
        $mode = $data['mode'] ?? 'cb';

        $reservationModel = new Reservation();
        $reservation = $reservationModel->getReservationById($id);

        if (!$reservation || $reservation['user_id'] != $_SESSION['user']['id']) {
            http_response_code(403);
            echo "Accès refusé.";
            return;
        }

        // On simule que le paiement a réussi
        $reservationModel->marquerCommePayee($id);

        // Redirection vers les réservations
        header('Location: /?page=mes_reservations');
        exit;
    }
}
