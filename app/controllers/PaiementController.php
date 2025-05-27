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

        $montant = $_GET['montant'] ?? null;

        if (!$montant || !is_numeric($montant)) {
            echo "<p class='alert alert-danger'>❌ Informations de paiement manquantes ou invalides.</p>";
            require_once __DIR__ . '/../views/templates/footer.php';
            return;
        }

        // Redirection vers la page avec bouton PayPal
        header('Location: /?page=valider_paiement&id=' . urlencode($reservationId) . '&montant=' . urlencode($montant));
        exit;
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
